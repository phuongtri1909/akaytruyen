@extends('Admin.layouts.main')
@section('page_title')
    {{ $story->name }}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="text-dark mb-2">{{ $story->name ?? '' }}</h3>
            <form id="add-edit-chapter" class="form-validate" method="post" autocomplete="off"
                action="@if ($chapter && $chapter->id) {{ route('admin.chapter.update', $chapter->id) }}
            @else
            {{ route('admin.chapter.store') }} @endif"
                enctype="multipart/form-data">
                @csrf
                @if ($chapter && $chapter->id)
                    @method('put')
                @else
                    @method('post')
                    <input type="hidden" name="story_id" value="{{ $story->id }}">
                @endif
                <div class="row mb-1">
                    <div class="col-6">
                        <label class="form-label" for="basic-icon-default-name-{{ $chapter && $chapter->id }}">Tên chương</label>
                        <input type="text" id="basic-icon-default-name-{{ $chapter && $chapter->id }}" class="form-control"
                            name="name" value="{{ ($chapter && $chapter->name) ? $chapter->name : '' }}" required />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-6">
                        <label class="form-label" for="basic-icon-default-name-2-{{ $chapter && $chapter->id }}">Số chương</label>
                        <input type="text" id="basic-icon-default-name-2-{{ $chapter && $chapter->id }}" class="form-control"
                            name="chapter" value="{{ ($chapter && $chapter->chapter) ? $chapter->chapter : '' }}" required />
                    </div>
                </div>

                <div class="row mb-1">
                <span>Nội dung</span>
                <textarea name="content" class="form-control" rows="10">
    {{ old('content', strip_tags(html_entity_decode($chapter->content ?? '', ENT_QUOTES, 'UTF-8'))) }}
</textarea>
                </div>

                <div class="row">
                    <div class="col-6 col-md-3">
                        {!! FormUi::checkbox('is_new', 'Chương mới', '', $errors, $chapter) !!}
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success me-1">
                        {{ $chapter && $chapter->id ? 'Cập nhật' : 'Tạo mới' }}
                    </button>
                    <a href="{{ route('admin.story.show', $story->id) }}" class="btn btn-secondary me-1">
                        <i data-feather='rotate-ccw'></i>
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

