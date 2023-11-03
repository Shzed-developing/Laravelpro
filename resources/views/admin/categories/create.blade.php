@component('admin.layouts.content', ['title' => 'ایجاد دستبه بندی '])


    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">لیست دستبه بندی ها</a></li>
        <li class="breadcrumb-item active">ایجاد دستبه بندی  جدید</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            @include('admin.layouts.errors')
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">فرم ایجاد دستبه بندی </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="card-body">

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">دسته مرتبط</label>
                            <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="نام دستبه بندی  را وارد کنید">
                        </div>
                        @if(request('parent'))
                            @php
                                $parent = \App\Models\Category::find(request('parent'))
                            @endphp
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">نام دستبه بندی </label>
                                <input type="text" class="form-control" id="inputEmail3" disabled value="{{ $parent->name }}">
                                <input type="hidden" name="parent" value="{{ $parent->id }}">
                            </div>
                        @endif
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" class="btn btn-info">ثبت دستبه بندی </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-default float-left">لغو</a>
                  </div>
                  <!-- /.card-footer -->
                </form>
              </div>

        </div>
    </div>

@endcomponent
