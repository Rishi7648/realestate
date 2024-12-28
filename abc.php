<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        h2 {
            color: #444;
            margin-bottom: 10px;
        }

        .property {
            background-color: #fafafa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .property h3 {
            color: #333;
            font-size: 1.2em;
        }

        .property p {
            color: #555;
            margin: 5px 0;
        }

        .property h4 {
            margin-top: 15px;
            color: #666;
        }

        .property img {
            max-width: 200px;
            margin: 5px;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            margin: 10px 5px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .approve {
            background-color: #28a745;
            color: white;
        }

        .approve:hover {
            background-color: #218838;
        }

        .reject {
            background-color: #dc3545;
            color: white;
        }

        .reject:hover {
            background-color: #c82333;
        }

        .btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .property {
                padding: 15px;
            }

            .property img {
                max-width: 150px;
            }

            .btn {
                font-size: 0.9em;
                padding: 8px 16px;
            }
        }

        /* User Details Styles */
        .user-details {
            background-color: #fff;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .user-details h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .user-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-details table th, .user-details table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .user-details table th {
            background-color: #f8f8f8;
            color: #333;
        }

        .user-details table td {
            color: #555;
        }

        /* Tab Styles */
        .tab-btns {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .tab-btn:hover {
            background-color: #0056b3;
        }

        .tab-btn.active {
            background-color: #0056b3;
        }

        .property-list {
            display: none;
        }

        .property-list.active {
            display: block;
        }
    </style>