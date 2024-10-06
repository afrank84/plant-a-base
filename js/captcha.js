let captchaAnswer;

        function generateCaptcha() {
            const num1 = Math.floor(Math.random() * 10) + 1;
            const num2 = Math.floor(Math.random() * 10) + 1;
            captchaAnswer = num1 + num2;
            document.getElementById('captcha-container').textContent = `Prove your a human: What is ${num1} + ${num2}?`;
        }

        generateCaptcha();

        document.getElementById('signup-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            const userAnswer = parseInt(document.getElementById('captcha-answer').value, 10);
            
            if (userAnswer !== captchaAnswer) {
                document.getElementById('signup-message').textContent = 'Incorrect CAPTCHA answer. Please try again. Maybe try using your fingers...';
                generateCaptcha();
                return;
            }

            // Here you would typically send the email to your server
            console.log('Email submitted:', email);
            
            document.getElementById('signup-message').textContent = 'Thank you for signing up! We\'ll keep you updated.';
            this.reset();
            generateCaptcha();
        });
