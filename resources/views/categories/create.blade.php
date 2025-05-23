<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.21.0/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css">
    <title></title>
    <meta name="csrf_token" content="{{ csrf_token() }}" />
</head>
<body>



<div class="row">
    <!-- Button trigger modal -->
<div class="col-md-6 offset-3" style="margin-top:100px">
    <button type="button" class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#categoryModal" id="add_category">
    Add Category
    </button>
 <!-- Table -->
 <table class="table " id="category-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Type</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>
</div>
 

<!-- Modal -->
<form id="ajaxform">
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group mb-4">
            <label for="category" class="mb-2 fw-bold">Name</label>
            <input type="text" class="form-control "  name="name" id="name"  aria-describedby="name" placeholder="Category">
            <span id="nameError" class="text-danger error-messages"></span>  
        </div>
        <div >
          <input type="hidden" id="category_id" name="category_id" > 
        </div>
            <div class="form-group ">
                <select name="type"  id="type" class="form-select">
                    <option disabled selected >Choose Option</option>
                    <option value="electronic" >Electronic</option>
                </select>
                <span id="typeError" class="text-danger error-messages"></span>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveBtn" >Save changes</button>
      </div>

    </div>
  </div>
</div>
</form>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
<script>

$( document ).ready(function() {
        
        $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')}
            });
           var tbl =  $('#category-table').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{route('categories.index')}}",
              columns: [
                {data:'id'},
                {data:'name'},
                {data:'type'},
                {data:'action',name:'action', orderable:false,searchable:false},
              ]
            });
        $('#modal-title').html('create category');
        var form = $('#ajaxform')[0];

        $('#saveBtn').click(function(event){
            event.preventDefault();
            $('.error-messages').html(' ');
            var formData = new FormData(form);
            $.ajax({
                url: "{{route('categories.store')}}",
                 method: 'POST',
                 processData: false,
                 contentType: false,
                 data:formData,
                 success: function(response){
                  tbl.draw()
                  $('#categoryModal').modal('hide')
                  $('#ajaxform')[0].reset()
                  if (response) {
                      Swal.fire({title:'Successful !',
                         text: response.success,
                         icon:'success'
                        })
                     }
                    
                 },
                 error: function(error){
                    console.log(error.responseJSON.errors.name)
                    console.log(error.responseJSON.errors.type)
                    if(error){
                        $('#nameError').html(error.responseJSON.errors.name)
                        $('#typeError').html(error.responseJSON.errors.type)
                    
                    }
                 }
            })
        })

        //edit button
        $('body').on('click','.editButton',function(){
          
          var id = $(this).data('id');
        
          $.ajax({
            url:'/categories'+'/'+id+'/edit',
            method: 'GET',
            success: function(response){
              $('#modal-title').html('Edit category');
              $('#saveBtn').html('update category')
              $('#categoryModal').modal('show')
              var type = capitalizeFirstLetter(response.type)
              $('#name').val(capitalizeFirstLetter(response.name))
              $('#category_id').val(response.id)
              $('#type').empty().append('<option selected value="'+response.type+'">'+type+'</option>')
              
              
            },
            error: function(error){
              console.log(error)
            },

          })
        });
        
        $('body').on('click','.delButton',function(){
          Swal.fire({
              title: "Are you sure?",
              text: "You won't be able to revert this!",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Yes, delete it!"
            }).then((result) => {
              if (result.isConfirmed) {

                  var id = $(this).data('id')
                  
                  $.ajax({
                    url:'/categories/destroy'+'/'+id,
                    method: 'DELETE',
                    success: function(response){
                      tbl.draw()
                      if (response) {
                              Swal.fire({title:'Successful !',
                                text: response.success,
                                icon:'success'
                                })
                            } 
                      
                    },
                    error: function(error){
                      console.log(error)
                    },

                  })

                }// end sweetart if
              });// end sweet alert function
          
        })// End delete function





        $('#add_category').click(function(){
          $('#saveBtn').html('save category')
          $('#modal-title').html('Create Category')
          $('#name').val(' ')
          $('#type').val(' ')
        })
        function capitalizeFirstLetter(string){
          return string.charAt(0).toUpperCase()+string.slice(1)
        }
    });
</script>
</html>