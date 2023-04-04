@extends('layouts.admin')

@push('stylesheets')

@endpush

@section('content')
    <!-- CONTAINER -->
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
    @include('blogs::tags.partial.header')
        <!-- PAGE-HEADER END -->

        <!-- ROW -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">افزودن تگ</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tags.store') }}" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                            @include('admin.partial.lang')
                            @include('admin.partial.brand')
                            <div class="col-md-12">
                                <label for="title" class="form-label">نام</label>
                                <input type="text" name="name" class="form-control" id="name" required value="{{ old('name') }}">
                                <div class="invalid-feedback">لطفا نام را وارد کنید</div>
                            </div>
{{--                            <div class="col-md-6">--}}
{{--                                <label for="slug" class="form-label">نامک</label>--}}
{{--                                <input type="text" name="slug" class="form-control" id="slug" required value="{{ old('slug') }}">--}}
{{--                                <div class="invalid-feedback">لطفا نامک را وارد کنید</div>--}}
{{--                            </div>--}}
                            <div class="col-md-12">
                                <label for="page_title" class="form-label">عنوان صفحه</label>
                                <input type="text" name="page_title" class="form-control" value="{{ old('page_title') }}" required>
                                <div class="invalid-feedback">لطفا عنوان صفحه را وارد کنید</div>
                            </div>
                            <div class="col-md-12">
                                <label for="meta_keywords" class="form-label">کلمات کلیدی</label>
                                <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}">
                                <div class="invalid-feedback">لطفا کلمات کلیدی را وارد کنید</div>
                            </div>
                            <div class="col-md-12">
                                <label for="meta_description" class="form-label">توضیحات سئو</label>
                                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}">
                                <div class="invalid-feedback">لطفا توضیحات سئو را وارد کنید</div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">ارسال فرم</button>
                                @csrf
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ROW CLOSED -->

    </div>

    @push('scripts')

    @endpush
@endsection
