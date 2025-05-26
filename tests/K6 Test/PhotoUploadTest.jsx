import { sleep, group, check } from "k6";
import http from "k6/http";
import { SharedArray } from "k6/data";

// Test configuration
export const options = {
    stages: [
        { target: 1, duration: "10s" },  // Ramp up to 1 user
        { target: 3, duration: "20s" },  // Stay at 5 users
        { target: 2, duration: "10s" },  // Ramp down to 2 users
    ],
    thresholds: {
        http_req_duration: ["p(95)<5000"], // 95% of requests should be below 5s
        http_req_failed: ["rate<0.1"],     // Less than 10% of requests should fail
    },
};

// Login credentials
const jsonData = JSON.parse(open("./data.json")).users;

// Sample image data - you'll need to provide a test image
const testImage = open("./test-image.jpg", "b");

// Helper function to generate random title
function generateRandomTitle() {
    const adjectives = ['Beautiful', 'Historic', 'Amazing', 'Vintage', 'Rare', 'Unique', 'Classic', 'Modern', 'Ancient', 'Famous'];
    const nouns = ['Photo', 'Image', 'Picture', 'Snapshot', 'Portrait', 'Scene', 'Moment', 'Memory', 'Artifact', 'Document'];
    const randomNum = Math.floor(Math.random() * 1000);
    const randomAdj = adjectives[Math.floor(Math.random() * adjectives.length)];
    const randomNoun = nouns[Math.floor(Math.random() * nouns.length)];
    return `${randomAdj} ${randomNoun} ${randomNum}`;
}

// Helper function to parse cookies
function parseCookies(cookieString) {
    const cookies = {};
    if (!cookieString) return cookies;
    
    cookieString.split(';').forEach(cookie => {
        const parts = cookie.split('=');
        if (parts.length === 2) {
            cookies[parts[0].trim()] = parts[1].trim();
        }
    });
    return cookies;
}

export default function main() {
    let response;
    let token;
    let sessionCookie;

    // Get random user from the array
    const randomUser = jsonData[Math.floor(Math.random() * jsonData.length)];

    // First, login to get authentication
    group("login", function () {
        // Get CSRF token
        response = http.get(
            "http://kizarukaede.indonesiacentral.cloudapp.azure.com/login",
            {
                headers: {
                    "Accept": "application/json",
                }
            }
        );

        // Parse cookies from response
        const cookies = parseCookies(response.headers["Set-Cookie"]);
        
        if (cookies["XSRF-TOKEN"]) {
            token = decodeURIComponent(cookies["XSRF-TOKEN"]);
        }
        if (cookies["galeri_sejarah_session"]) {
            sessionCookie = `galeri_sejarah_session=${cookies["galeri_sejarah_session"]}`;
        }

        const loginPayload = JSON.stringify({
            email: randomUser.username,
            password: randomUser.password,
        });

        const loginParams = {
            headers: {
                "Content-Type": "application/json",
                "X-XSRF-TOKEN": token,
                "Accept": "application/json",
                "Cookie": sessionCookie
            },
        };

        const loginRes = http.post(
            "http://kizarukaede.indonesiacentral.cloudapp.azure.com/login",
            loginPayload,
            loginParams
        );

        // Parse cookies from login response
        const loginCookies = parseCookies(loginRes.headers["Set-Cookie"]);
        
        if (loginCookies["galeri_sejarah_session"]) {
            sessionCookie = `galeri_sejarah_session=${loginCookies["galeri_sejarah_session"]}`;
        }
        if (loginCookies["XSRF-TOKEN"]) {
            token = decodeURIComponent(loginCookies["XSRF-TOKEN"]);
        }


        check(loginRes, {
            "login successful": (r) => r.status === 200,
        });

        // If login fails, don't proceed with upload
        if (loginRes.status !== 200) {
            return;
        }
    });

    sleep(1);

    // Test photo upload
    group("upload photo", function () {
        // Get fresh CSRF token for upload
        const csrfResponse = http.get(
            "http://kizarukaede.indonesiacentral.cloudapp.azure.com/api/content-photo",
            {
                headers: {
                    "Accept": "application/json",
                    "Cookie": sessionCookie
                }
            }
        );

        // Parse cookies from CSRF response
        const csrfCookies = parseCookies(csrfResponse.headers["Set-Cookie"]);
        let uploadToken = token;
        
        if (csrfCookies["XSRF-TOKEN"]) {
            uploadToken = decodeURIComponent(csrfCookies["XSRF-TOKEN"]);
        }
        if (csrfCookies["galeri_sejarah_session"]) {
            sessionCookie = `galeri_sejarah_session=${csrfCookies["galeri_sejarah_session"]}`;
        }

        const formData = {
            title: generateRandomTitle(),
            description: "Test photo upload description",
            image: http.file(testImage, "test-image.jpg", "image/jpeg"),
            source: "Test Source",
            alt_text: "Test Alt Text",
            note: "Test Note",
            tag: "test,photo,upload",
        };

        const uploadParams = {
            headers: {
                "X-XSRF-TOKEN": uploadToken,
                "Cookie": sessionCookie,
                "Accept": "application/json"
            },
        };

        const uploadRes = http.post(
            "http://kizarukaede.indonesiacentral.cloudapp.azure.com/api/content-photo",
            formData,
            uploadParams
        );

   
        check(uploadRes, {
            "upload successful": (r) => r.status === 201,
            "response has message": (r) => r.json("message") !== undefined,
            "response has data": (r) => r.json("data") !== undefined,
        });
    });

    sleep(2);
} 