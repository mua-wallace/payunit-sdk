<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Description" content="Enter your description here" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Success Page</title>
</head>
<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        list-style: none;
        text-decoration: none;
    }
    body{
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #eeeeee;
        
    }
    section{
        width: 50%;
        height: 80vh;
        box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.2),
            0px 0px 30px 0px rgba(0,0,0,0.1);
    }
    .box_1{
        width: 100%;
        height: 50%;
        background-color: #3EA49A;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;

    }   
    .box_2{
        width: 100%;
        height: 50%;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: flex-start;

    }
    .box_2 h1{
        color: #14223F;
        margin-top: 1rem;
        font-family: Arial, Helvetica, sans-serif;
    }   
    .success-message p{
        margin-top: 2rem;
        text-align: center;
        font-family: Arial, Helvetica, sans-serif;
    }
    .back{
        margin: 2rem auto;
        padding: 15px 20px;
        font-size: 15px;
        color: #fff;
        background: #EC8629;
        display: flex;
        justify-content: center;
        width: fit-content;
        align-self: center;
        border-radius: 50px;
        font-weight: bold;
    }
</style>

<body>
    <section>
    
        <div class="box_1">
            <!-- <i class="fa fa-check-circle-o" aria-hidden="true"></i>
            <i class="fas fa-check-circle-o" ></i> -->
            <i class="fas fa-check-circle fa-5x"></i>
        </div>
        <div class="box_2">
            <div class="success-message">
                <h1>Transaction successfull</h1>
                <p>Thank you for your Donation !</p>
                <a class="back" href="./index.php"><i class="fas fa-arrow-right- fa-2x"></i> Home</a>
            </div>
        </div>
    </section>
</body>

</html>