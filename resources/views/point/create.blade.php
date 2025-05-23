<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.21.0/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title></title>
    <meta name="csrf_token" content="{{ csrf_token() }}" />
</head>
<body>



<div class="row">
    <!-- Button trigger modal -->
<div class="col-md-6 offset-3" style="margin-top:100px">
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#categoryModal">
    Add Category
    </button>
</div>
</div>
<!-- Modal -->
<form id="ajaxform">
    @csrf
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
            <input type="text" class="form-control "  name="name" aria-describedby="name" placeholder="Category">
            <span id="nameError" class="text-danger"></span>  
        </div>
            <div class="form-group ">
                <select name="type"   class="form-select">
                   <option disabled selected >Choose Option</option>
                    <option value="electronic" >Electronic</option>
                </select>
                <span id="typeError" class="text-danger"></span>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
      </div>

    </div>
  </div>
</div>
</form>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

$( document ).ready(function() {
  
    $('#modal-title').html('Create Category')  
    $('#saveBtn').html('Save Category')

    $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')}
     });

    var form = $('#ajaxform')[0]
      $('#saveBtn').click(function(){
        var formData = new FormData(form);
        $('.error-messages').html(' ');

        $.ajax({
          url: "{{route('categories.store')}}",
          method: 'POST',
          processData:false,
          contentType:false,
          data: formData,
          success: function(response){
            $('#categoryModal').modal('hide');
            if (response) {
              Swal.fire({title:'Successful !',
                         text: response.success,
                         icon:'success'
                        })
            }
          },
          error: function(error){
            if(error){
                        $('#nameError').html(error.responseJSON.errors.name)
                        $('#typeError').html(error.responseJSON.errors.type)
                    
                    }
          }
        })

      })
    });
</script>
</html>