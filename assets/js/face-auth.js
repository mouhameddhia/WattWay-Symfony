export class FaceAuth {
    static async captureFace() {
        const video = document.createElement('video');
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'user' } 
            });
            
            video.srcObject = stream;
            await video.play();
            
            // Set canvas size
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw face
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Stop stream
            stream.getTracks().forEach(track => track.stop());
            
            return canvas.toDataURL('image/jpeg').split(',')[1];
        } catch (error) {
            console.error('Error capturing face:', error);
            throw error;
        }
    }
}