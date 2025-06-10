import axios from "axios";

const getCmsName = async () => {
    try {
        const { data } = await axios.get("/api/company-profile", {
            headers: {
                Accept: "application/json",
            },
        });

        // Get the CMS name from the correct API structure
        const cmsName = data.data?.cms_name;

        // Log for debugging
        console.log("API Response:", data);
        console.log("CMS Name:", cmsName);

        // Return the watermark text with proper fallback
        return cmsName ? `© ${cmsName}` : "© Galeri Sejarah";
    } catch (error) {
        console.error("Error fetching CMS name:", error);
        return "© Galeri Sejarah"; // fallback text
    }
};

export const addWatermarkToImage = async (imageFile) => {
    return new Promise(async (resolve, reject) => {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        const image = new Image();

        // Get watermark text from API
        const watermarkText = await getCmsName();

        image.onload = () => {
            canvas.width = image.width;
            canvas.height = image.height;

            // Draw original image
            ctx.drawImage(image, 0, 0);

            // Configure watermark
            ctx.fillStyle = "rgba(255, 255, 255, 0.2)";
            ctx.font = `${image.width * 0.08}px Arial`;

            // Calculate position (center)
            const metrics = ctx.measureText(watermarkText);
            const x = (canvas.width - metrics.width) / 2;
            const y = canvas.height / 2;

            // Add rotation for diagonal watermark
            ctx.save();
            ctx.translate(x + metrics.width / 2, y);
            ctx.rotate(-Math.PI / 6);
            ctx.fillText(watermarkText, -metrics.width / 2, 0);
            ctx.restore();

            canvas.toBlob((blob) => {
                const watermarkedFile = new File([blob], imageFile.name, {
                    type: imageFile.type,
                    lastModified: Date.now(),
                });
                resolve(watermarkedFile);
            }, imageFile.type);
        };

        image.onerror = reject;
        image.src = URL.createObjectURL(imageFile);
    });
};

export const addWatermarkToVideo = async (videoFile) => {
    return new Promise(async (resolve, reject) => {
        const video = document.createElement("video");
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        // Get watermark text from API
        const watermarkText = await getCmsName();

        video.onloadedmetadata = () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const stream = canvas.captureStream();
            const mediaRecorder = new MediaRecorder(stream, {
                mimeType: "video/webm",
            });

            const chunks = [];
            mediaRecorder.ondataavailable = (e) => chunks.push(e.data);
            mediaRecorder.onstop = () => {
                const blob = new Blob(chunks, { type: "video/webm" });
                const watermarkedFile = new File([blob], videoFile.name, {
                    type: "video/webm",
                    lastModified: Date.now(),
                });
                resolve(watermarkedFile);
            };

            video.onplay = () => {
                const drawFrame = () => {
                    if (video.paused || video.ended) return;

                    // Draw current video frame
                    ctx.drawImage(video, 0, 0);

                    // Configure watermark
                    ctx.fillStyle = "rgba(255, 255, 255, 0.3)";
                    ctx.font = `${video.videoWidth * 0.08}px Arial`;

                    // Calculate position (center)
                    const metrics = ctx.measureText(watermarkText);
                    const x = (canvas.width - metrics.width) / 2;
                    const y = canvas.height / 2;

                    // Add rotation for diagonal watermark
                    ctx.save();
                    ctx.translate(x + metrics.width / 2, y);
                    ctx.rotate(-Math.PI / 6);
                    ctx.fillText(watermarkText, -metrics.width / 2, 0);
                    ctx.restore();

                    requestAnimationFrame(drawFrame);
                };

                mediaRecorder.start();
                drawFrame();
            };

            video.onended = () => {
                mediaRecorder.stop();
            };

            video.play();
        };

        video.onerror = reject;
        video.src = URL.createObjectURL(videoFile);
    });
};
