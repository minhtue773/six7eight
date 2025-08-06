<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        .wrapper {
            /* background: linear-gradient(to bottom, #30cfd0, #330867); */
            background: linear-gradient(45deg, #E4C7B7, #8B4C70);
        }

        .scroll-custom {
            font-size: 14px;
            max-height: 450px;
            overflow: auto;
            scroll-behavior: smooth;
        }

        .title {
            color: red;
        }
    </style>
</head>

<body>
    @php
        $baseURL = 'api/';
    @endphp
    <div
        class="wrapper container-fluid bg-primary d-flex flex-column justify-content-center align-items-center min-vh-100">
        <h1 class="text-white my-5">Document API</h1>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-info">
                            Response Data Format
                        </div>
                        <img src="images/Picture1.png" class="card-img-bottom img-fluid w-full" alt="...">
                    </div>
                </div>
                <div class="col-md-8 col-12 mb-4">
                    <div class="container">
                        <div class="accordion accordion-flush" id="accordionExample">
                            {{-- Auth --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingAuth">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseAuth" aria-expanded="true"
                                        aria-controls="collapseAuth">
                                        Auth API
                                    </button>
                                </h2>
                                <div id="collapseAuth" class="accordion-collapse collapse show"
                                    aria-labelledby="headingAuth" data-bs-parent="#accordionExample">
                                    <div class="accordion-body scroll-custom">

                                        <!-- Đăng nhập -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Đăng nhập</div>
                                                    <div><mark>{{ $baseURL }}auth/login</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>email</li>
                                                    <li>password</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Đăng ký -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Đăng ký tài khoản</div>
                                                    <div><mark>{{ $baseURL }}auth/register</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>name, email, password</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>
                                        <!-- Đăng ký -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Xác thực email</div>
                                                    <div><mark>{{ $baseURL }}auth/verify-email</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Param</strong>
                                                <ul>
                                                    <li>Token</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Lấy thông tin người dùng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Lấy thông tin người dùng</div>
                                                    <div><mark>{{ $baseURL }}auth/profile</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Làm mới token -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Làm mới token</div>
                                                    <div><mark>{{ $baseURL }}auth/refresh</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Đăng xuất -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Đăng xuất</div>
                                                    <div><mark>{{ $baseURL }}auth/logout</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Cập nhật thông tin -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-warning rounded-3 text-white block me-3">PATCH</div>
                                                <div>
                                                    <div class="title">Cập nhật thông tin người dùng</div>
                                                    <div><mark>{{ $baseURL }}auth/update/{id}</mark></div>
                                                </div>
                                            </div>
                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>name, phone_number, address, gender, birthday</li>
                                                    <li>password (nếu đổi mật khẩu)</li>
                                                    <li>photo (avatar)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xóa tài khoản -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xóa tài khoản người dùng</div>
                                                    <div><mark>{{ $baseURL }}auth/delete/{id}</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- User --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingUser">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseUser" aria-expanded="true"
                                        aria-controls="collapseUser">
                                        User API
                                    </button>
                                </h2>
                                <div id="collapseUser" class="accordion-collapse collapse" aria-labelledby="headingUser"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body scroll-custom">
                                        <!-- Lấy danh sách người dùng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Lấy danh sách người dùng</div>
                                                    <div><mark>{{ $baseURL }}users</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Query Params</strong>
                                                <ul>
                                                    <li><code>q</code>: Tìm kiếm theo tên, email, địa chỉ, số điện thoại
                                                        (string)</li>
                                                    <li><code>status</code>: Lọc theo trạng thái (giá trị: 0, 1, 2)</li>
                                                    <li><code>role</code>: Lọc theo vai trò (giá trị: admin, customer)
                                                    </li>
                                                    <li><code>page</code>: Trang hiện tại (number)</li>
                                                    <li><code>limit</code>: Số lượng bản ghi mỗi trang (number)</li>
                                                    <li><code>sort_by</code>: Trường sắp xếp (giá trị: name, created_at,
                                                        email...)</li>
                                                    <li><code>sort_order</code>: Kiểu sắp xếp (giá trị: asc, desc)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>
                                        <!-- Lấy chi tiết người dùng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Lấy chi tiết người dùng</div>
                                                    <div><mark>{{ $baseURL }}users/{id}</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Tạo người dùng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Tạo mới người dùng</div>
                                                    <div><mark>{{ $baseURL }}users</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>name, email, password</li>
                                                    <li>role, status</li>
                                                    <li>phone_number, address, gender, birthday</li>
                                                    <li>photo (upload avatar - optional)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Cập nhật người dùng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-warning rounded-3 text-white block me-3">PATCH</div>
                                                <div>
                                                    <div class="title">Cập nhật người dùng</div>
                                                    <div><mark>{{ $baseURL }}users/{id}</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>name, role, status</li>
                                                    <li>phone_number, address, gender, birthday</li>
                                                    <li>password (nếu muốn cập nhật)</li>
                                                    <li>photo (upload avatar - optional)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xoá người dùng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xoá người dùng</div>
                                                    <div><mark>{{ $baseURL }}users/{id}</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xoá nhiều người dùng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xoá nhiều người dùng</div>
                                                    <div><mark>{{ $baseURL }}users/destroy</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>ids: mảng các ID cần xoá</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xoá vĩnh viễn -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xoá vĩnh viễn người dùng đã xoá mềm</div>
                                                    <div><mark>{{ $baseURL }}users/force-delete</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>ids: mảng các ID cần xoá vĩnh viễn</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Khôi phục người dùng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-warning rounded-3 text-white block me-3">PATCH</div>
                                                <div>
                                                    <div class="title">Khôi phục người dùng đã xoá mềm</div>
                                                    <div><mark>{{ $baseURL }}users/restore</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>ids: mảng các ID cần khôi phục</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Product Category --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCategory">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCategory" aria-expanded="true"
                                        aria-controls="collapseCategory">
                                        Product Categories API
                                    </button>
                                </h2>
                                <div id="collapseCategory" class="accordion-collapse collapse"
                                    aria-labelledby="headingCategory" data-bs-parent="#accordionExample">
                                    <div class="accordion-body scroll-custom">

                                        <!-- Lấy danh sách danh mục -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Lấy danh sách danh mục sản phẩm</div>
                                                    <div><mark>{{ $baseURL }}product-categories</mark></div>
                                                </div>
                                            </div>

                                            <div><strong>Query Params:</strong>
                                                <ul>
                                                    <li><code>q:</code> tìm kiếm theo tên</li>
                                                    <li><code>limit:</code> số lượng bản ghi mỗi trang</li>
                                                    <li><code>sort_by:</code> name, is_featured, created_at, updated_at
                                                    </li>
                                                    <li><code>sort_order:</code> asc, desc</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Chi tiết danh mục -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Chi tiết danh mục sản phẩm</div>
                                                    <div><mark>{{ $baseURL }}product-categories/{id}</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Thêm danh mục -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Tạo mới danh mục sản phẩm</div>
                                                    <div><mark>{{ $baseURL }}product-categories</mark></div>
                                                </div>
                                            </div>

                                            <div><strong>Body:</strong>
                                                <ul>
                                                    <li>name: tên danh mục</li>
                                                    <li>is_featured: 1 hoặc 0</li>
                                                    <li>is_hidden: 1 hoặc 0</li>
                                                    <li>photo: ảnh thumbnail (tệp ảnh)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Cập nhật danh mục -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-warning rounded-3 text-white block me-3">PATCH</div>
                                                <div>
                                                    <div class="title">Cập nhật danh mục sản phẩm</div>
                                                    <div><mark>{{ $baseURL }}product-categories/{id}</mark></div>
                                                </div>
                                            </div>

                                            <div><strong>Body:</strong>
                                                <ul>
                                                    <li>name: tên danh mục</li>
                                                    <li>is_featured: 1 hoặc 0</li>
                                                    <li>is_hidden: 1 hoặc 0</li>
                                                    <li>photo: ảnh mới (nếu có)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xoá danh mục -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xoá danh mục sản phẩm</div>
                                                    <div><mark>{{ $baseURL }}product-categories/{id}</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- Brand --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingBrand">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBrand" aria-expanded="true"
                                        aria-controls="collapseBrand">
                                        Brand API
                                    </button>
                                </h2>
                                <div id="collapseBrand" class="accordion-collapse collapse"
                                    aria-labelledby="headingBrand" data-bs-parent="#accordionExample">
                                    <div class="accordion-body scroll-custom">

                                        <!-- Lấy danh sách thương hiệu -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Lấy danh sách thương hiệu</div>
                                                    <div><mark>{{ $baseURL }}brands</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Query Params</strong>
                                                <ul>
                                                    <li><code>q:</code> Tìm kiếm theo tên (string)</li>
                                                    <li><code>limit:</code> Số lượng bản ghi mỗi trang (number)</li>
                                                    <li><code>sort_by:</code> Sắp xếp theo (values: name, created_at)
                                                    </li>
                                                    <li><code>sort_order:</code> Thứ tự sắp xếp (values: asc, desc)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Lấy chi tiết thương hiệu -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Lấy chi tiết thương hiệu</div>
                                                    <div><mark>{{ $baseURL }}brands/{id}</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Tạo mới thương hiệu -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Tạo mới thương hiệu</div>
                                                    <div><mark>{{ $baseURL }}brands</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>name: Tên thương hiệu (bắt buộc)</li>
                                                    <li>is_hidden: Ẩn hay không (0 hoặc 1)</li>
                                                    <li>is_featured: Nổi bật (0 hoặc 1)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Cập nhật thương hiệu -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-warning rounded-3 text-white block me-3">PATCH</div>
                                                <div>
                                                    <div class="title">Cập nhật thương hiệu</div>
                                                    <div><mark>{{ $baseURL }}brands/{id}</mark></div>
                                                </div>
                                            </div>

                                            <div>
                                                <strong>Body</strong>
                                                <ul>
                                                    <li>name: Tên thương hiệu</li>
                                                    <li>is_hidden: Ẩn hay không (0 hoặc 1)</li>
                                                    <li>is_featured: Nổi bật (0 hoặc 1)</li>
                                                </ul>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xóa thương hiệu -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xóa thương hiệu</div>
                                                    <div><mark>{{ $baseURL }}brands/{id}</mark></div>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- Product --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingProduct">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseProduct" aria-expanded="true"
                                        aria-controls="collapseProduct">
                                        Product API
                                    </button>
                                </h2>
                                <div id="collapseProduct" class="accordion-collapse collapse"
                                    aria-labelledby="headingProduct" data-bs-parent="#accordionExample">
                                    <div class="accordion-body scroll-custom">

                                        <!-- Lấy danh sách sản phẩm -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Danh sách sản phẩm (public)</div>
                                                    <mark>{{ $baseURL }}products</mark>
                                                </div>
                                            </div>

                                            <strong>Query Parameters:</strong>
                                            <ul>
                                                <li><code>q</code> – Từ khóa tìm kiếm theo tên, mô tả</li>
                                                <li><code>status</code> – Lọc theo trạng thái (1: active, 0:
                                                    inactive...)</li>
                                                <li><code>category_id</code> – ID danh mục</li>
                                                <li><code>brand_id</code> – ID thương hiệu</li>
                                                <li><code>gender</code> – <code>male</code> | <code>female</code> |
                                                    <code>unisex</code>
                                                </li>
                                                <li><code>sort_by</code> – Cột sắp xếp (mặc định:
                                                    <code>created_at</code>)
                                                </li>
                                                <li><code>sort_order</code> – asc | desc</li>
                                                <li><code>limit</code> – Số lượng mỗi trang (mặc định: 10)</li>
                                                <li><code>page</code> – Trang hiện tại</li>
                                            </ul>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Chi tiết sản phẩm -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Chi tiết sản phẩm</div>
                                                    <mark>{{ $baseURL }}products/{id}</mark>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Tạo sản phẩm -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Tạo mới sản phẩm</div>
                                                    <mark>{{ $baseURL }}products</mark>
                                                </div>
                                            </div>

                                            <strong>Body (multipart/form-data):</strong>
                                            <ul>
                                                <li><code>name</code> – Tên sản phẩm (string, required)</li>
                                                <li><code>slug</code> – Slug sản phẩm (string, required, unique)</li>
                                                <li><code>description</code> – Mô tả (string, optional)</li>
                                                <li><code>brand_id</code> – ID thương hiệu (integer, required)</li>
                                                <li><code>category_id</code> – ID danh mục (integer, required)</li>
                                                <li><code>gender</code> – <code>male</code> | <code>female</code> |
                                                    <code>unisex</code> (default: unisex)
                                                </li>
                                                <li><code>status</code> – Trạng thái (0~5)</li>
                                                <li><code>view</code> – Lượt xem (integer, optional)</li>
                                                <li><code>main_image</code> – Ảnh chính (image file, required)</li>
                                                <li><code>images[]</code> – Ảnh phụ (multiple image files, optional)
                                                </li>
                                                <li><code>variants[]</code> – Mảng biến thể (dưới dạng array object):
                                                    <pre>[
{
    "size_id": 1,
    "color_id": 2,
    "price": 199000,
    "stock": 10,
    "sku": "SKU001"
},
    ...
]</pre>
                                                </li>
                                            </ul>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Cập nhật sản phẩm -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-warning rounded-3 text-white block me-3">PATCH</div>
                                                <div>
                                                    <div class="title">Cập nhật sản phẩm</div>
                                                    <mark>{{ $baseURL }}products/{id}</mark>
                                                </div>
                                            </div>

                                            <strong>Body (multipart/form-data):</strong>
                                            <ul>
                                                <li>Tất cả các trường giống <code>POST</code>, nhưng đều
                                                    <code>optional</code>
                                                </li>
                                                <li>Chỉ truyền những gì cần cập nhật</li>
                                                <li><code>main_image</code> – Nếu cập nhật ảnh chính</li>
                                                <li><code>images[]</code> – Nếu muốn thêm ảnh phụ mới</li>
                                                <li><code>variants[]</code> – Nếu muốn ghi đè biến thể</li>
                                            </ul>
                                            <hr class="my-1">
                                        </div>
                                        <!-- Xoá mềm sản phẩm -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xoá mềm sản phẩm</div>
                                                    <mark>{{ $baseURL }}products/{id}</mark>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xoá mềm nhiều sản phẩm -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xoá mềm nhiều sản phẩm</div>
                                                    <mark>{{ $baseURL }}products</mark>
                                                </div>
                                            </div>

                                            <strong>Body:</strong>
                                            <ul>
                                                <li><code>ids</code>: Mảng các ID sản phẩm cần xoá</li>
                                            </ul>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xoá vĩnh viễn -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xoá vĩnh viễn sản phẩm</div>
                                                    <mark>{{ $baseURL }}products/force-delete</mark>
                                                </div>
                                            </div>

                                            <strong>Body</strong>
                                            <ul>
                                                <li><code>ids</code>: Mảng các ID sản phẩm cần xoá vĩnh viễn</li>
                                            </ul>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Khôi phục sản phẩm -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-warning rounded-3 text-white block me-3">PATCH</div>
                                                <div>
                                                    <div class="title">Khôi phục sản phẩm đã xoá mềm</div>
                                                    <mark>{{ $baseURL }}products/restore</mark>
                                                </div>
                                            </div>

                                            <strong>Body</strong>
                                            <ul>
                                                <li><code>ids</code>: Mảng các ID sản phẩm cần khôi phục</li>
                                            </ul>
                                            <hr class="my-1">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- Cart --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCart">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseCart"
                                        aria-expanded="false" aria-controls="collapseCart">
                                        Cart API
                                    </button>
                                </h2>
                                <div id="collapseCart" class="accordion-collapse collapse"
                                    aria-labelledby="headingCart" data-bs-parent="#accordionExample">
                                    <div class="accordion-body scroll-custom">

                                        <!-- Lấy giỏ hàng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-primary rounded-3 text-white block me-3">GET</div>
                                                <div>
                                                    <div class="title">Lấy giỏ hàng hiện tại</div>
                                                    <mark>{{ $baseURL }}cart</mark>
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Thêm vào giỏ hàng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success rounded-3 text-white block me-3">POST</div>
                                                <div>
                                                    <div class="title">Thêm sản phẩm vào giỏ</div>
                                                    <mark>{{ $baseURL }}cart/add</mark>
                                                </div>
                                            </div>

                                            <strong>Body (JSON):</strong>
                                            <ul>
                                                <li><code>product_variant_id</code> – ID biến thể sản phẩm (required)
                                                </li>
                                                <li><code>quantity</code> – Số lượng (integer, min: 1, required)</li>
                                            </ul>
                                            <strong>Response:</strong>
                                            <pre>{
  "success": true,
  "message": "Đã thêm vào giỏ hàng!"
}</pre>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Cập nhật giỏ hàng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-warning rounded-3 text-white block me-3">PATCH</div>
                                                <div>
                                                    <div class="title">Cập nhật số lượng sản phẩm</div>
                                                    <mark>{{ $baseURL }}cart/update</mark>
                                                </div>
                                            </div>

                                            <strong>Body (JSON):</strong>
                                            <ul>
                                                <li><code>product_variant_id</code> – ID biến thể sản phẩm (required)
                                                </li>
                                                <li><code>quantity</code> – Số lượng mới (integer, min: 1, required)
                                                </li>
                                            </ul>
                                            <strong>Response:</strong>
                                            <pre>{
  "success": true,
  "message": "Đã cập nhật giỏ hàng!"
}</pre>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xóa sản phẩm khỏi giỏ -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xóa sản phẩm khỏi giỏ</div>
                                                    <mark>{{ $baseURL }}cart/remove</mark>
                                                </div>
                                            </div>

                                            <strong>Body (JSON):</strong>
                                            <ul>
                                                <li><code>product_variant_id</code> – ID biến thể sản phẩm cần xóa</li>
                                            </ul>
                                            <strong>Response:</strong>
                                            <pre>{
  "success": true,
  "message": "Đã xóa sản phẩm khỏi giỏ hàng!"
}</pre>
                                            <hr class="my-1">
                                        </div>

                                        <!-- Xóa toàn bộ giỏ hàng -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-danger rounded-3 text-white block me-3">DELETE</div>
                                                <div>
                                                    <div class="title">Xóa toàn bộ giỏ hàng</div>
                                                    <mark>{{ $baseURL }}cart/clear</mark>
                                                </div>
                                            </div>

                                            <strong>Response:</strong>
                                            <pre>{
  "success": true,
  "message": "Đã xóa toàn bộ giỏ hàng!"
}</pre>
                                            <hr class="my-1">
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
