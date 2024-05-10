function validateForm() {
  var name = document.forms["contactForm"]["name"].value;
  var email = document.forms["contactForm"]["email"].value;
  var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

  if (name == "") {
      alert("Name must be filled out");
      return false;
  }

  if (!email.match(emailRegex)) {
      alert("Invalid email format");
      return false;
  }

  return true;
}
