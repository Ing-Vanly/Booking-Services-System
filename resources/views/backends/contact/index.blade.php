@extends('backends.master')

@section('contents')

<div class="card-body p-0 table-wrapper">
    <h1 class="text-center mb-4">{{ __('Contact') }}</h1>

    <!-- Success Message -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Contacts Table -->
    <div class="card-body p-0 table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Message') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contacts as $contact)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="ml-2">
                                {{ $contact->name }}
                            </span>
                        </td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ $contact->message }}</td>
                        <td>
                            @can('admin.contact.destroy')
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline-block form-delete-{{ $contact->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                        <i class="fa fa-trash-alt"></i> {{ __('Delete') }}
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('No contacts found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="row">
            <div class="col-12 d-flex flex-row flex-wrap">
                <div class="row" style="width: -webkit-fill-available;">
                    <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                        {{ __('Showing') }} {{ $contacts->firstItem() }} {{ __('to') }} {{ $contacts->lastItem() }} {{ __('of') }} {{ $contacts->total() }} {{ __('entries') }}
                    </div>
                    <div class="col-12 col-sm-6 pagination-nav pr-3">
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
