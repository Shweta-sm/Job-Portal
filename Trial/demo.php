<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #333;
        }

        .header .logout-btn {
            padding: 8px 16px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .header .logout-btn:hover {
            background-color: #e53935;
        }

        .profile-info {
            text-align: center;
            margin-top: 15px;
            color: #555;
        }

        .profile-info span {
            font-weight: bold;
        }

        .filter-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            /* Center the filters */
            gap: 15px;
            margin-bottom: 20px;
        }

        .filter-fields {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            width: 100%;
        }

        .filter-fields select,
        .filter-fields input {
            width: 220px;
            /* Consistent width */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Center the buttons */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            /* Space between buttons */
            width: 100%;
            margin-top: 10px;
        }

        button {
            padding: 12px 18px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        #resetBtn {
            background-color: #f44336;
        }

        #resetBtn:hover {
            background-color: #e53935;
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            .filter-fields select,
            .filter-fields input {
                width: 100%;
                /* Full width on mobile */
            }
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table td {
            color: #555;
        }

        /* Button Styling */
        .apply-btn,
        .view-btn {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        .apply-btn {
            background-color: #007BFF;
            color: white;
        }

        .apply-btn:hover {
            background-color: #0056b3;
        }

        .view-btn {
            background-color: #28a745;
            color: white;
        }

        .view-btn:hover {
            background-color: #218838;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }

            select,
            input[type="text"] {
                width: 100%;
            }

            .container {
                padding: 10px;
            }

            .header h1 {
                font-size: 1.5em;
            }
        }

        /**/
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Job Seeker Dashboard</h1>
            <button class="logout-btn">Logout</button>
        </div>


        <h2>Filter Jobs</h2>
        <div class="filter-container">
            <div class="filter-fields">
                <!-- Search Bar -->
                <input type="text" id="searchInput" placeholder="Search by Job Title, Company, Location, or Job Type">

                <!-- Filter Dropdowns -->
                <select id="job_type">
                    <option value="">Select Job Type</option>
                    <option value="full_time">Full Time</option>
                    <option value="part_time">Part Time</option>
                    <option value="contract">Contract</option>
                </select>

                <input type="text" id="location" placeholder="Enter Location">

                <select id="salary">
                    <option value="">Select Salary Range</option>
                    <option value="2-5">2-5 LPA</option>
                    <option value="5-10">5-10 LPA</option>
                    <option value="10-15">10-15 LPA</option>
                </select>
            </div>

            <!-- Buttons centered below the fields -->
            <div class="button-container">
                <button id="filterBtn">Filter</button>
                <button id="resetBtn">Reset</button>
            </div>
        </div>

        <div id="job_results">
            <!-- Job table will be populated here -->
        </div>
    </div>

    <script>
        function loadJobs() {
            let job_type = $('#job_type').val();
            let job_title = $('#searchInput').val();
            let location = $('#location').val();
            let salary = $('#salary').val();

            $.ajax({
                url: "demofetch.php",
                type: "GET",
                data: {
                    job_type,
                    job_title,
                    location,
                    salary
                },
                success: function(response) {
                    $('#job_results').html(response);
                }
            });
        }

        $('#filterBtn').click(function() {
            loadJobs();
        });

        $('#resetBtn').click(function() {
            $('#job_type').val('');
            $('#searchInput').val('');
            $('#location').val('');
            $('#salary').val('');
            loadJobs();
        });

        $(document).ready(function() {
            loadJobs();
        });
    </script>

</body>

</html>