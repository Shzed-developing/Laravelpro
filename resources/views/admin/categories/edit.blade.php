@component('admin.layouts.content', ['title' => 'ویرایش دستبه بندی '])


    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">لیست دستبه بندی ها</a></li>
        <li class="breadcrumb-item active">ویرایش دستبه بندی  جدید</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            @include('admin.layouts.errors')
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">فرم ویرایش دستبه بندی </h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="card-body">

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">نام دستبه بندی </label>
                            <input type="text" name="name" class="form-control" id="inputEmail3" placeholder="نام دستبه بندی  را وارد کنید" value="{{ old('name', $category->name) }}">
                        </div>

                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" class="btn btn-info">ویرایش دستبه بندی </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-default float-left">لغو</a>
                  </div>
                  <!-- /.card-footer -->
                </form>
              </div>

        </div>
    </div>

@endcomponent
