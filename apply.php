<?php
    session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!--  page info -->
  <meta name="description" content="Innovexa Labs Apply Form">
  <meta name="keywords" content="Innovexa, Labs, Apply, Form">
  <meta name="author" content="G05">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Apply - Innovexa Labs</title>

  <!-- External CSS file -->
  <link href="styles/styles.css" rel="stylesheet">

  <!-- Inline style for labels -->
  <style>
    label { font-weight: bold; }
  </style>
</head>

<body>
  <!-- ================== HEADER ================== -->
  <?php include_once "header.inc"; ?>

  <!-- ================= MAIN CONTENT ================= -->
  <main>
    <h2>Job Application Form</h2>

    <!-- Form that submits data to il_db -->
    <form action="process_eoi.php" method="post" novalidate>
      
      <!-- Job Reference Input -->
      <p>
        <label for="jobref">Job Reference Number:</label>
        <input type="text" id="jobref" name="jobref" maxlength="5" size="5" pattern="[A-Za-z0-9]{5}" required>
      </p>

      <!-- Personal Details Section -->
      <fieldset>
        <legend>Personal Details</legend>

        <!-- First Name -->
        <p>
          <label for="firstname">First Name:</label>
          <input type="text" id="firstname" name="firstname" maxlength="20" pattern="[A-Za-z]{1,20}" required>
        </p>

        <!-- Last Name -->
        <p>
          <label for="lastname">Last Name:</label>
          <input type="text" id="lastname" name="lastname" maxlength="20" pattern="[A-Za-z]{1,20}" required>
        </p>

        <!-- Date of Birth -->
        <p>
          <label for="dob">Date Of Birth:</label>
          <input type="date" id="dob" name="dob" required>
        </p>

        <!-- Gender Selection -->
        <fieldset>
          <legend>Gender:</legend>
          <input type="radio" id="male" name="gender" value="Male" required>
          <label for="male">Male</label>

          <input type="radio" id="female" name="gender" value="Female" required>
          <label for="female">Female</label>

          <input type="radio" id="other" name="gender" value="Other" required>
          <label for="other">Other</label>
        </fieldset>
      </fieldset>

      <!-- Address Section -->
      <fieldset>
        <legend>Address</legend>

        <p>
          <label for="street">Street Address:</label>
          <input type="text" id="street" name="street" maxlength="40" required>
        </p>
        <p>
          <label for="suburb">Suburb/Town:</label>
          <input type="text" id="suburb" name="suburb" maxlength="40" required>
        </p>
        <p>
          <label for="state">State:</label>
          <select id="state" name="state" required>
            <option value="">Please Select</option>
            <option value="VIC">VIC</option>
            <option value="NSW">NSW</option>
            <option value="QLD">QLD</option>
            <option value="NT">NT</option>
            <option value="WA">WA</option>
            <option value="SA">SA</option>
            <option value="TAS">TAS</option>
            <option value="ACT">ACT</option>
          </select>
        </p>
      </fieldset>

      <!-- Contact Details Section -->
      <fieldset>
        <legend>Contact Details</legend>
        <p>
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </p>
        <p>
          <label for="phone">Phone Number:</label>
          <input type="text" id="phone" name="phone" maxlength="12" size="12" pattern="[0-9]{8,12}" required>
        </p>
      </fieldset>

      <!-- Skills Section -->
      <fieldset>
        <legend>Skill List</legend>

        <!-- Skills Checkboxes -->
        <input type="checkbox" id="html" name="skills[]" value="HTML">
        <label for="html">HTML</label>

        <input type="checkbox" id="css" name="skills[]" value="CSS">
        <label for="css">CSS</label>

        <input type="checkbox" id="ux" name="skills[]" value="UX">
        <label for="ux">UX</label>

        <input type="checkbox" id="otherskills" name="skills[]" value="OtherSkills">
        <label for="other">Other</label>

        <!-- Textarea for extra skills -->
        <p>
          <label for="otherskillsdesc">Other Skills (Optional):</label>
          <textarea id="otherskillsdesc" name="otherskillsdesc" rows="4" cols="40"></textarea>
        </p>
      </fieldset>

      <!-- Submit and Reset Buttons -->
      <p>
        <button type="submit">Submit</button>
        <button type="reset">Reset</button>
      </p>
    </form>
  </main>

  <!-- ================== FOOTER ================== -->
  <?php include_once "footer.inc"; ?>
  
</body>
</html>
