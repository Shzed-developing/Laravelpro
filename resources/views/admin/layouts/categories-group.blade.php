<ul class="list-group list-group-flush">
    @foreach($categories as $category)
        <li class="list-group-item">
            <div class="d-flex">
                <span>{{ $category->name }}</span>
                <div class="actions mr-2">
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" id="category-{{ $category->id }}-delete" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('category-{{ $category->id }}-delete').submit()" class="badge badge-danger">حذف</a>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="badge badge-primary">ویرایش</a>
                    <a href="{{ route('admin.categories.create') }}?parent={{$category->id}}" class="badge badge-warning">ثبت زیر دسته</a>
                </div>
                <!-- /.actions -->
            </div>
            <!-- /.d-flex -->
            @if($category->child->count())
                @include('admin.layouts.categories-group', ['categories' => $category->child])
            @endif
        </li>
    @endforeach
</ul>
