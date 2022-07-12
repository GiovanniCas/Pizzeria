@php
    use App\Models\Header;
    use App\Models\SelectedProduct;
@endphp
<?php
    
    // $username="root";
    // $password="";
    // $database="pizzeria";

    // try{
    //     $pdo = new PDO("mysql:host=localhost;database=pizzeria" , "root");
    //     //$pdo->settAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // } catch(PDOExeption $e){
    //     die("ERROR: Could not connect. " . $e->getMessage());
    // } 
?> 
<x-layout> 
    <h1>Report Ultima Settimana :</h1>
     <?php 
    
     
    // try{
    //     $sql = "SELECT * FROM users";
    //     $result = $pdo->query($sql);
    //     if($result->rowCount() > 0) {
    //         while($row = $result->fetch()) {
    //             echo $row["id"];
    //         }
    //         unset($result);
    //     }else{
    //         echo "no records";
    //     }
    // }catch(PDOException $e){
    //     die("error" . $e->getMessage());
    // }
    // unset($pdo);
     ?> 

    <div>
        <canvas id="myChart"  height="130"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');
        const ctx = document.getElementById('myChart').getContext('2d');
        const ctx = $('#myChart');
        const ctx = 'myChart';
    </script>

    <script>
        let data= @json($date);
        let totale= @json($totali_per_giorno);
        const ctx = document.getElementById('myChart');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data,
                datasets: [{
                    label: 'Guadagno',
                    data: totale,
                    backgroundColor: [
                        'red',
                        'blue',
                        'yellow',
                        'green',    
                        'purple',
                        'orange',
                        'white',
                    ],
                    borderColor: [
                        'red',
                        'blue',
                        'yellow',
                        'green',    
                        'purple',
                        'orange',
                        'white',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    
</x-layout> 

