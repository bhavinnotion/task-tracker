<!DOCTYPE html>
<html>
<head>
    <title>Task-Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>
<body>
    <nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand mr-auto" href="#">Task-Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register-user') }}">Register</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('signout') }}">Logout</a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <section class="vh-200" style="background-color: #eee;">
      <div class="container py-5 h-200">
        <div class="row d-flex justify-content-center align-items-center h-200">
          <div class="col col-lg-10 col-xl-7">
            <div class="card rounded-6">
              <div class="card-body p-8">
                <h4 class="text-center my-3 pb-5">Task List</h4>
                <div class="col-12">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalContactForm">Add-Task</a>
                </div>
                <table class="table mb-4">
                  <thead>
                    <tr>
                     <th></th>
                      <th scope="col">No.</th>
                      <th scope="col">Task Name</th>
                      <th scope="col">Start-time</th>
                      <th scope="col">End-time</th>
                      <th scope="col">Total Time</th>
                      <th scope="col" width="200px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($taskList as $task)
                    <tr>
                      @if($task->status == 'c') 
                        <td><input type="checkbox" name="task_list" checked></td>
                      @else
                        <td><input type="checkbox" name="task_list"></td>
                      @endif
                      <th scope="row">{{$task->id}}</th>
                      <td>{{$task->task_name}}</td>
                      @if($task->start_time == "") 
                        <td>00:00:00</td>
                      @else
                        <td>{{date('h:i:s A',strtotime($task->start_time))}}</td>
                      @endif
                      @if($task->end_time == "") 
                        <td>00:00:00</td>
                      @else
                        <td>{{date('h:i:s A',strtotime($task->end_time))}}</td>
                      @endif
                      <td>{{date_diff(new \DateTime($task->start_time), new \DateTime($task->end_time))->format("%h Hours, %i minits,%s seccond"); }}</td> 
                      @if($task->status == 'c') 
                      <td>
                        <button type="button" id="start_task" data-id="{{$task->id}}" class="btn btn-primary start_task" disabled>Start-task</button>&nbsp
                        <button type="button" id="end_task" data-id="{{$task->id}}" class="btn btn-danger end_task" disabled>End-task</button>
                      </td>
                      @else
                      <td>
                        <button type="button" id="start_task" data-id="{{$task->id}}" class="btn btn-primary start_task">Start-task</button>&nbsp
                        <button type="button" id="end_task" data-id="{{$task->id}}" class="btn btn-danger end_task">End-task</button>
                      </td>
                      @endif
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@yield('content')
</body>
</html>
<div class="modal fade" id="modalContactForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" action="{{ route('add_task') }}">
            @csrf
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Add-Task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">
                <div class="md-form mb-5">
                  <label data-error="wrong" data-success="right" for="form34">Task Name</label>
                  <input type="text" id="task_name" name="task_name" class="form-control validate" required>
                </div>

                <div class="md-form mb-5">
                  <label data-error="wrong" data-success="right" for="form8">Task Deatils</label>
                  <textarea type="text" id="task_details" name="task_details" class="md-textarea form-control" rows="4"></textarea>
                </div>
           </div>

          <div class="modal-footer d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Add-Task</button>
          </div>
        </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).on("click",".start_task",function() {
        var task_id = $(this).data('id');
        
        $.ajax({
             type:"POST",
             dataType : 'json',
             url: "{{ route('check_task') }}",
             data : {"action":"check_task",task_id},
             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
             success: function (response) 
             {  
                alert("Task Time Start");
                location.reload();
             },
        });
    });

    $(document).on("click",".end_task",function() {
        var task_id = $(this).data('id');

        $.ajax({
             type:"POST",
             dataType : 'json',
             url: "{{ route('end_task') }}",
             data : {"action":"end_task",task_id},
             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
             success: function (response) 
             {  
                alert("Task Time End");
                location.reload();
             },
        });
    });
</script>