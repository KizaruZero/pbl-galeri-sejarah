import { parseHTML } from "k6/html";
import { sleep, group, check } from "k6";
import http from "k6/http";

export const options = {
    stages: [
        { target: 50, duration: "1m" },
        { target: 100, duration: "0" },
        { target: 200, duration: "1m30s" },
    ],
};

// Login credentials
const jsonData = JSON.parse(open("./data.json")).users;

export default function main() {
    let response;
    let token;

    // Get random user from the array
    const randomUser = jsonData[Math.floor(Math.random() * jsonData.length)];

    group("get login page", function () {
        response = http.get(
            "http://kizarukaede.indonesiacentral.cloudapp.azure.com/login"
        );

        let xsrfTokenValue = null;
        let cookies = response.headers["Set-Cookie"];
        if (cookies) {
            let cookieArray = cookies.split(";");
            cookieArray.forEach((cookie) => {
                if (cookie.includes("XSRF-TOKEN")) {
                    xsrfTokenValue = cookie.split("=")[1].replace(/%3D$/, ""); // Remove '=%3D' from the end
                }
            });

            token = xsrfTokenValue;
        }

        check(response, {
            "is status 200": (r) => r.status === 200,
            "XSRF-TOKEN exists": (t) => t !== null,
        });
    });

    sleep(1);

    group("post login page", function () {
        const payload = JSON.stringify({
            username: randomUser.username,
            password: randomUser.password,
        });

        const params = {
            headers: {
                Connection: "keep-alive",
                "Content-Type": "application/json",
                "X-XSRF-TOKEN": token,
            },
        };

        const res = http.post(
            "http://kizarukaede.indonesiacentral.cloudapp.azure.com/login",
            payload,
            params
        );
        check(res, {
            "is status 200": (r) => r.status === 200,
            "is correct URL": (r) =>
                r.url.includes(
                    "kizarukaede.indonesiacentral.cloudapp.azure.com"
                ),
        });
    });

    sleep(1);
}
