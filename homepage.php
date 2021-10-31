<?php
  session_start();
  $user_id = $_SESSION['user_id'];
?>

<html>
  <head>
    <style type="text/css">
      .chartbox {
        width: 400px;
      }
    </style>
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
    />
  </head>
  <body>
    <!-- nav bar -->
    <nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="homepage.php">Expensses Tracker</a>
        </div>

        <button
          class="btn btn-primary navbar-btn"
          data-toggle="modal"
          data-target="#myModal"
        >
          Add Expense
        </button>
        <a class="btn btn-outline-secondary float-right" href = "php/logout.php">logout</a>
      </div>
    </nav>

    <!-- main container -->
    <div class="container">
      <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">
                &times;
              </button>
              <h4 class="modal-title">Add Expense</h4>
            </div>
            <div
              class="modal-body"
              style="padding-left: 20%; padding-right: 20%"
            >
              <input type="text" id="user_id" value="<?php echo $user_id?>" hidden />
              <label for="amount">Ammount</label>
              <input
                name="amount"
                type="number"
                class="form-control"
                id="amount"
                placeholder="1000$,500$,..."
                required
              />
              <label for="category">Category</label>
              <br />
              <select name="category" id="category" style="width: 40%">
                <option value="food">Food</option>
                <option value="water">Water</option>
                <option value="electricity">Electricity</option>
              </select>
              <br />
              <label for="date">Date</label>
              <input
                name="date"
                type="date"
                class="form-control"
                id="date"
                required
              />
              <br />
              <button
                class="btn btn-success float-right"
                id="add_btn"
                data-dismiss="modal"
              >
                Add Expense
              </button>
            </div>

            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- moddal 2 -->
      <div class="modal fade" id="myModal2" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">
                &times;
              </button>
              <h4 class="modal-title">Edit this Expense</h4>
            </div>
            <div
              class="modal-body"
              style="padding-left: 20%; padding-right: 20%"
            >
              <input type="text" id="expid2" value="" hidden />
              <label for="amount">Ammount</label>
              <input
                name="amount"
                type="number"
                class="form-control"
                id="amount2"
                placeholder="1000$,500$,..."
                required
              />
              <label for="category">Category</label>
              <br />
              <select name="category" id="category2" style="width: 40%">
                <option value="food">Food</option>
                <option value="water">Water</option>
                <option value="electricity">Electricity</option>
              </select>
              <br />
              <label for="date2">Date</label>
              <input
                name="date"
                type="date"
                class="form-control"
                id="date2"
                required
              />
              <br />
              <button
                class="btn btn-warning float-right Edit_btn"
                id=""
                data-dismiss="modal"
              >
                Edit Expense
              </button>
            </div>

            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- container for table and chart  -->
      <div class="container" style="justify-content: center">
        <!-- div table row -->
        <div class="row">
          <div class="col my-5">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>id</th>
                  <th>category</th>
                  <th>ammount</th>
                  <th>date</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="tbody">

              </tbody>
            </table>
          </div>
        </div>
        <!-- chart div row-->
        <div class="row my-5">
          <div class="chartbox">
            <canvas id="myChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script
      src="https://code.jquery.com/jquery-3.6.0.js"
      integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
      crossorigin="anonymous"
    ></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
<!-- ****************************************************************************************** -->
  <script>

    //GET_EXPENSES
    $(document).ready(getExpenses);

      async function getExpensesAPI(){
      const response = await fetch("http://localhost/FSW_Bootcamp/ExpenseTracker-AliDaana/php/get_expenses.php");
      
      if(!response.ok){
        const message = "ERROR OCCURED";
        throw new Error(message);
      }
      
      const results = await response.json();
      return results;
    }

      function getExpenses(){
      getExpensesAPI().then(results => {
        $.each(results, function(index, res){
          var $tr = $('<tr>').append(
            $('<td>').text(res.id),
            $('<td>').text(res.category),
            $('<td>').text(res.amount),
            $('<td>').text(res.date),           
            $('<td>').append("<button type='button' data-toggle='modal' data-target='#myModal2' id='" + res.id + "' class='btn btn-success editButn'>Edit Expense</button>"),
            $('<td>').append("<button type='button' id='" + res.id + "' class='btn btn-danger deleteBtn'>Delete</button>")
          ).appendTo("#tbody");
        })
        $(document).on('click', '.editButn', function() { $('.Edit_btn').attr("id",this.id) });
        $("#add_btn").click(addExpense);
        $(".deleteBtn").click(deleteArticle);
        $(".Edit_btn").click(editExpense);        
      });

      async function getChartAPI(){
          const response = await fetch("http://localhost/FSW_Bootcamp/ExpenseTracker-AliDaana/php/getChartExpenses.php");
          
          if(!response.ok){
            const message = "ERROR OCCURED";
            throw new Error(message);
          }
          
          const results = await response.json();
          return results;
        }

        function getChart(){
          getChartAPI().then(results => {
            console.log(results);

            const randomRGB = [];
            const label = [];
            const data2 = [];

            $(".chartbox").empty();
            $(".chartbox").append('<canvas id="myChart"></canvas>');

            function randomNum() {
              var rand = Math.floor(Math.random() * (235 - 52 + 1) + 52);
              return rand;
            }
            for (var i = 0; i < results.length; i++) {
              randomRGB[i] = `rgba(${randomNum()}, ${randomNum()}, ${randomNum()})`;
              label[i] = results[i]["name"] + "($)";
              data2[i] = results[i]["amount"];
            }

            const randomBorder = randomRGB;

            //setup block contain all datapoints and labels of chart
            const data = {
              labels: label,
              datasets: [
                {
                  label: "Expenses",
                  data: data2,
                  backgroundColor: randomRGB,
                  borderColor: randomBorder,
                  borderWidth: 1,
                },
              ],
            };

            // configuration block\
            const config = { type: "pie", data, options: {} };

            //render block
            const myChart = new Chart(document.getElementById("myChart"), config);

          });
        }

        getChart();

        


    }
// ==========================================================================================================================================

//ADD_EXPENSE
    async function addExpenseAPI(){
      const response = await fetch("http://localhost/FSW_Bootcamp/ExpenseTracker-AliDaana/php/add_expense.php?user_id="+$('#user_id').val()+"&category="+$('#category').val()+"&amount="+$('#amount').val()+"&date="+$('#date').val(), {
        method: 'POST',
        body: new URLSearchParams({
            id: $('#user_id').val(),
            category: $('#category').val(),
            amount: $('#amount').val(),
            date: $('#date').val(),
          }),
      });
      
      if(!response.ok){
        const message = "ERROR OCCURED";
        throw new Error(message);
      }
      
      const results = await response.json();
      return results;
    }

    function addExpense(){
      addExpenseAPI().then(results => {
        var $tr = $('<tr>').append(
          $('<td>').text(results.id),
          $('<td>').text(results.category),
          $('<td>').text(results.amount),
          $('<td>').text(results.date),
          $('<td>').append("<button type='button' data-toggle='modal' data-target='#myModal2' id='" + results.id + "' class='btn btn-success editButn'>Edit Expense</button>"),
          $('<td>').append("<button type='button' id='" + results.id + "' class='btn btn-danger deleteBtn'>Delete</button>")
          
        ).appendTo("#tbody");
        $(document).on('click', '.editButn', function() { $('.Edit_btn').attr("id",this.id) });
        $(".deleteBtn").click(deleteArticle);
        $(".Edit_btn").click(editExpense);
      });



      async function getChartAPI(){
          const response = await fetch("http://localhost/FSW_Bootcamp/ExpenseTracker-AliDaana/php/getChartExpenses.php");
          
          if(!response.ok){
            const message = "ERROR OCCURED";
            throw new Error(message);
          }
          
          const results = await response.json();
          return results;
        }

        function getChart(){
          getChartAPI().then(results => {
            console.log(results);

            const randomRGB = [];
            const label = [];
            const data2 = [];

            $(".chartbox").empty();
            $(".chartbox").append('<canvas id="myChart"></canvas>');

            function randomNum() {
              var rand = Math.floor(Math.random() * (235 - 52 + 1) + 52);
              return rand;
            }
            for (var i = 0; i < results.length; i++) {
              randomRGB[i] = `rgba(${randomNum()}, ${randomNum()}, ${randomNum()})`;
              label[i] = results[i]["name"] + "($)";
              data2[i] = results[i]["amount"];
            }

            const randomBorder = randomRGB;

            //setup block contain all datapoints and labels of chart
            const data = {
              labels: label,
              datasets: [
                {
                  label: "Expenses",
                  data: data2,
                  backgroundColor: randomRGB,
                  borderColor: randomBorder,
                  borderWidth: 1,
                },
              ],
            };

            // configuration block\
            const config = { type: "pie", data, options: {} };

            //render block
            const myChart = new Chart(document.getElementById("myChart"), config);

          });
        }

        getChart();

    }
//======================================================================================================================================================
//DELETE_EXPENSE

async function deleteArticleAPI(btn_id){
    const response = await fetch("http://localhost/FSW_Bootcamp/ExpenseTracker-AliDaana/php/delete_expense.php?id="+btn_id);
    
    if(!response.ok){
      const message = "ERROR OCCURED";
      throw new Error(message);
    }
    
    const results = await response.json();
    return results;
  }

function deleteArticle(){

  var btn_id = this.id;
  
  deleteArticleAPI(btn_id).then(response => {
    console.log(response["success"]);
    if(response["success"] == 1)
    {
      $(this).parent().parent().remove();


      async function getChartAPI(){
          const response = await fetch("http://localhost/FSW_Bootcamp/ExpenseTracker-AliDaana/php/getChartExpenses.php");
          
          if(!response.ok){
            const message = "ERROR OCCURED";
            throw new Error(message);
          }
          
          const results = await response.json();
          return results;
        }

        function getChart(){
          getChartAPI().then(results => {
            console.log(results);

            const randomRGB = [];
            const label = [];
            const data2 = [];

            $(".chartbox").empty();
            $(".chartbox").append('<canvas id="myChart"></canvas>');

            function randomNum() {
              var rand = Math.floor(Math.random() * (235 - 52 + 1) + 52);
              return rand;
            }
            for (var i = 0; i < results.length; i++) {
              randomRGB[i] = `rgba(${randomNum()}, ${randomNum()}, ${randomNum()})`;
              label[i] = results[i]["name"] + "($)";
              data2[i] = results[i]["amount"];
            }

            const randomBorder = randomRGB;

            //setup block contain all datapoints and labels of chart
            const data = {
              labels: label,
              datasets: [
                {
                  label: "Expenses",
                  data: data2,
                  backgroundColor: randomRGB,
                  borderColor: randomBorder,
                  borderWidth: 1,
                },
              ],
            };

            // configuration block\
            const config = { type: "pie", data, options: {} };

            //render block
            const myChart = new Chart(document.getElementById("myChart"), config);

          });
        }

        getChart();
      
    }
    
  });
}


//=========================================================================================================================================================
//EDIT_EXPENSE

async function editExpenseAPI(id){
  
      const response = await fetch("http://localhost/FSW_Bootcamp/ExpenseTracker-AliDaana/php/edit_expense.php", {
        method: 'POST',
        body: new URLSearchParams({
            id: id,
            category2: $('#category2').val(),
            amount2: $('#amount2').val(),
            date2: $('#date2').val(),
          }),
      });
      
      if(!response.ok){
        const message = "ERROR OCCURED";
        throw new Error(message);
      }
      
      const results = await response.json();
      return results;
    }

    function editExpense(){
      var id = this.id;

      editExpenseAPI(id).then(results => {

        $('#'+id+'.editButn').parent().parent().remove();

        var $tr = $('<tr>').append(
          $('<td>').text(results.id),
          $('<td>').text(results.category),
          $('<td>').text(results.amount),
          $('<td>').text(results.date),
          $('<td>').append("<button type='button' data-toggle='modal' data-target='#myModal2' id='" + results.id + "' class='btn btn-success editButn'>Edit Expense</button>"),
          $('<td>').append("<button type='button' id='" + results.id + "' class='btn btn-danger deleteBtn'>Delete</button>")
        ).appendTo("#tbody");
        $(document).on('click', '.editButn', function() { $('.Edit_btn').attr("id",this.id) });
        $(".deleteBtn").click(deleteArticle);
        $("#Edit_btn").click(editExpense);

        async function getChartAPI(){
          const response = await fetch("http://localhost/FSW_Bootcamp/ExpenseTracker-AliDaana/php/getChartExpenses.php");
          
          if(!response.ok){
            const message = "ERROR OCCURED";
            throw new Error(message);
          }
          
          const results = await response.json();
          return results;
        }

        function getChart(){
          getChartAPI().then(results => {
            console.log(results);

            const randomRGB = [];
            const label = [];
            const data2 = [];

            $(".chartbox").empty();
            $(".chartbox").append('<canvas id="myChart"></canvas>');

            function randomNum() {
              var rand = Math.floor(Math.random() * (235 - 52 + 1) + 52);
              return rand;
            }
            for (var i = 0; i < results.length; i++) {
              randomRGB[i] = `rgba(${randomNum()}, ${randomNum()}, ${randomNum()})`;
              label[i] = results[i]["name"] + "($)";
              data2[i] = results[i]["amount"];
            }

            const randomBorder = randomRGB;

            //setup block contain all datapoints and labels of chart
            const data = {
              labels: label,
              datasets: [
                {
                  label: "Expenses",
                  data: data2,
                  backgroundColor: randomRGB,
                  borderColor: randomBorder,
                  borderWidth: 1,
                },
              ],
            };

            // configuration block\
            const config = { type: "pie", data, options: {} };

            //render block
            const myChart = new Chart(document.getElementById("myChart"), config);

          });
        }

        getChart();

      });

      


    }





  </script>


  </body>
</html>
