<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container main">
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Simple Blog</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" id="search" type="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
    <div class="container pt-5">
        <div class="row">
            <!-- left side -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- blog lists -->
                    <div class="container table-responsive py-4">
                        <div class="row col-lg-12 my-2">
                            <div class="d-flex justify-content-end">
                                <button data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                    class="btn btn-primary">Create</button>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="row-container">
                                <!-- blog lists -->
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center align-items-center my-5" id="spinner"></div>

                    </div>
                </div>
            </div>

            <!-- Modal Add Post -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Create New Post</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body container">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title">
                            </div>
                        </div>
                        <div>
                            <label for="body" class="form-label container">Content</label>
                        </div>
                        <div class="form-floating m-3">
                            <textarea class="form-control" id="body" style="height: 100px"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save">Save</button>
                        </div>
                    </div>
                </div>
                <!-- Button trigger modal -->
            </div>

            <!-- Modal Delete Post -->
            <div class="modal fade" id="openModalDeletePost" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            Are You Sure to Delete This Post?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                            <input type="hidden" id="confirm-delete-post-id" />
                            <button type="button" class="btn btn-danger" id="btn-delete-final">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(function() {
        const container = $("#row-container")

        loadPosts()

        async function loadPosts() {
            await showLoadingSpinner();

            setTimeout(async () => {
                await getPost();
                await hideLoadingSpinner();
            }, 970);
        }

        $(document).on("click", ".btn-delete", function() {
            const id = $(this).data("id");
            $("#openModalDeletePost").modal("show")
            $("#confirm-delete-post-id").val(id)
        })

        $("#btn-delete-final").on("click", function() {
            const id = $(this).prev().val()
            deletePost(id)
            $("#openModalDeletePost").modal("hide")
        })

        $("#btn-save").on("click", function() {
            let title = $("#title").val()
            let body = $("#body").val()

            createPost(title, body)
        })

        function getPost() {
            $.ajax({
                type: 'GET',
                url: 'http://localhost:8000/api/posts',
                success: function(response) {
                    response.data.map((blog, index) => {
                        const card = `
                            <tr>
                              <th scope="row">${index + 1}</th>
                              <td>${blog.title}</td>
                              <td>${blog.body}</td>
                              <td>
                              <button data-id="${blog.id}" type="button" class="btn btn-danger btn-delete container row col-md-6 mx-auto">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>
                              </button>
                              </td>
                          </tr>
                        `;

                        container.append(card)
                    })
                }
            })
        }

        function createPost(title, body) {
            $.ajax({
                type: 'POST',
                url: 'http://localhost:8000/api/posts',
                processData: false,
                contentType: false,
                data: JSON.stringify({
                    title,
                    body
                }),
                success: function() {
                    $(".modal").modal("hide")
                    updatePostTable()
                    clearInput()
                }
            })
        }

        function deletePost(id) {
            $.ajax({
                method: 'DELETE',
                url: 'http://localhost:8000/api/posts/' + id,
                success: function() {
                    updatePostTable()
                }
            })
        }

        function updatePostTable() {
            $("#row-container").html('')
            getPost()
        }

        function showLoadingSpinner() {
            $("#row-container").html('')
            $("#spinner").html(
                `
                  <div class=" spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                `
            )
        }

        function hideLoadingSpinner() {
            $("#spinner").html('')
        }

        function clearInput() {
            $("#title").val('')
            $("#body").val('')
        }
    })
    </script>
</body>

</html>