
<!DOCTYPE HTML>
	
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title> Wyszukiwarka filmów </title>
	<meta name="description" content="Wyszukiwarka" />
	<meta name="keywords" content="elastic,search,baza,danych,wyszukiwarka" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>

<body>
	
	<div id="container">

        <p> WYSZUKAJ SWÓJ ULUBIONY FILM LUB SERIAL </p>

        <div class="form-group">
            <input type="text" id="search" name="search" placeholder="Search..." autocomplete="off" />
        </div> 

        <div id="match-list"></div>

	</div>
    
</body>

</html>

<script>
    
    $(document).ready(function(){
        $('#search').keyup(function(){
            var query = $(this).val();
            if(query != '')
            {
                $.ajax({
                    url: "search.php",
                    method:"POST",
                    data:{ query:query },
                    success:function(data)
                    {
                        $('#match-list').fadeIn();
                        $('#match-list').html(data);
                    }
                });
            }
            else
            {
                $('#match-list').fadeOut();
                $('#match-list').html("");
            }
        });
        $(document).on('click', 'li', function(){
            $('#search').val($(this).text());
            $('#match-list').fadeOut();
        });
    });

</script>
