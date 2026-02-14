@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>{{__('Contact')}}</h1>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createContactModal">Add Contact</button>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

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
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ $contact->message }}</td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editContactModal{{ $contact->id }}">
                                <i class="fas fa-pencil-alt"></i> {{ __('Edit') }}
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash-alt"></i> {{ __('Delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Contact Modal -->
                    <div class="modal fade" id="editContactModal{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="editContactModalLabel{{ $contact->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editContactModalLabel{{ $contact->id }}">Edit Contact</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name:</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $contact->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email:</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $contact->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone:</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $contact->phone }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="message" class="form-label">Message:</label>
                                            <textarea class="form-control" id="message" name="message" required>{{ $contact->message }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination Section --}}
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

<!-- Create Contact Modal -->
<div class="modal fade" id="createContactModal" tabindex="-1" role="dialog" aria-labelledby="createContactModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createContactModalLabel">Add Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contacts.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message:</label>
                        <textarea class="form-control" id="message" name="message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
