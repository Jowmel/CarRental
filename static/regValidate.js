const validation = new JustValidate("#signup");

validation
  .addField("#username", [
    {
      rule: "required",
      message: "Username is required",
    },
  ])
  .addField("#password", [
    {
      rule: "required",
      message: "Password is required",
    },
    {
      rule: "password",
      message: "Invalid password format",
    },
  ])
  .addField("#password_confirmation", [
    {
      validator: (value, fields) => value === fields["#password"].value,
      message: "Passwords must match",
    },
  ])
  .addField("#image", [
    {
      rule: "file",
      message: "Select image for profile picture",
    },
  ]);
