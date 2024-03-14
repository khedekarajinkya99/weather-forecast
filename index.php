<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    .main {
      background-image: url('./images/background.jpg');
      background-repeat: no-repeat;
      background-size: cover;
    }

    header {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 1em;
    }

    main {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form {
      max-width: 400px;
      margin: 0 auto;
    }

    label {
      display: block;
      margin-bottom: 8px;
    }

    input {
      width: 100%;
      padding: 8px;
      margin-bottom: 16px;
      box-sizing: border-box;
      border-radius: 6px;
    }

    button {
      width: 100px;
      height: 30px;
      font-size: 15px;
      border-radius: 5px;
      background-color: blue;
      color: white;
      border: 1px solid blue;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .error {
      width: 100%;
      height: 20px;
      margin-top: 2px;
      color: red;
      display: none;
    }

    .error-text {
      text-align: center;
    }

    .current-data tr, th, td {
      background-color: white;
      border: 0px;
    }

    @media screen and (max-width: 400px) {
      main {
        padding: 10px;
      }

      form {
        max-width: 100%;
      }

      .table {
        max-width: 100%;
        overflow-y: auto;
      }
    }

    @media screen and (max-width: 1240px) {
      main {
        padding: 10px;
      }

      form {
        max-width: 100%;
      }

      .table {
        max-width: 100%;
        overflow-y: auto;
      }
    }
  </style>
  <title>Responsive Form and Table</title>
</head>
<body class="main">
  <main>
    <form id="locationForm">
      <label for="name">Location:</label>
      <input type="text" id="location" name="location" placeholder="Location" autocomplete="off" required>
      <button type="submit" id="submitBtn" style="cursor: pointer;">Submit</button>
    </form>
    <div class="error">
      <p class="error-text"></p>
    </div>

    <table class="current-data">
      <thead>
        <tr>
          <th width="20%">Current Day, Date and Time</th>
          <th width="50%" style="text-align: center;">Temperature</th>
          <th width="20%">Location</th>
        </tr>
      </thead>
      <tbody id="currentData">
        <tr>
          <td id="currentDate"></td>
          <td id="currentTemp" style="text-align: center"></td>
          <td id="currentLocation"></td>
        </tr>
      </tbody>
    </table>

    <div class="table">
      <table>
        <thead>
          <tr>
            <th>Monday Temp</th>
            <th>Tuesday Temp</th>
            <th>Wednesday Temp</th>
            <th>Thursday Temp</th>
            <th>Friday Temp</th>
            <th>Saturday Temp</th>
            <th>Sunday Temp</th>
          </tr>
        </thead>
        <tbody id="forecastData">
          <tr>
            <td colspan="7" style="text-align: center;">Data Not Found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    $("#locationForm").submit(function(e) {
      e.preventDefault();
      var location = $("#location").val();
      if (location == "") {
        $(".error-text").text('Please enter the location.');
        $(".error").show();
      } else {
        $(".error").hide();
      }
      $("#submitBtn").prop('disabled', true);
      $.ajax({
        url: 'location.php',
        type: 'POST',
        data: {location: location},
        success: function(response) {
          var data = JSON.parse(response);
          var currentData = "";
          var currentTemp = "";
          var currentLocation = "";

          if (data.status == 'error') {
            $(".error-text").text(data.message);
            $(".error").show();
            var forecastHtml = "<tr><td colspan='7' style='text-align: center;''>Data Not Found</td></tr>";
          } else {
            $(".error").hide();

            currentData = data.data.current.current_data;
            currentTemp = data.data.current.temperature;
            currentLocation = data.data.current.location;

            var forecastHtml = "<tr>";
            $.each(data.data.forecast, function(key, val) {
              forecastHtml += "<td>"+val+"</td>";
            });
            forecastHtml += "</tr>";
          }

          $("#currentDate").text(currentData);
          $("#currentTemp").text(currentTemp);
          $("#currentLocation").text(currentLocation);
          $("#forecastData").html(forecastHtml);

          $("#submitBtn").prop('disabled', false);
        }
      })
    });
  });
</script>
</body>
</html>
