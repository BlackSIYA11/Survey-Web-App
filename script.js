async function handleSubmit(event) {
    event.preventDefault(); // Stop normal form submission (no reload)

    const form = event.target;
    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: form.method,
        body: formData
      });

      const text = await response.text();

      // Show the server response (you can change this alert to display inline)
      alert(text);

      // Reset the form inputs
      form.reset();

      // Redirect to home page after 3 seconds
      setTimeout(() => {
        window.location.href = "index.html"; 
      }, 3000);

    } catch (error) {
      alert('Submission failed: ' + error);
    }

    return false;
  }

  function validateForm() {
            const fullname = document.getElementById('fullname').value.trim();
            const email = document.getElementById('email').value.trim();
            const dob = document.getElementById('dob').value;
            const food = document.getElementById('food').value.trim();
            const errorMsg = document.getElementById('errorMsg');
            errorMsg.textContent = '';

            if (!fullname || !email || !dob || !food) {
                errorMsg.textContent = 'Please fill in all required fields.';
                return false;
            }

            // Validate Age between 5 and 120 based on DOB
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if (age < 5 || age > 120) {
                errorMsg.textContent = 'Age must be between 5 and 120 years.';
                return false;
            }

            // Check ratings
            const ratings = ['rate_movies', 'rate_radio', 'rate_eatout', 'rate_tv'];
            for (let rate of ratings) {
                const checked = document.querySelector(`input[name="${rate}"]:checked`);
                if (!checked) {
                    errorMsg.textContent = 'Please select a rating for all categories.';
                    return false;
                }
            }

            return true;
        }