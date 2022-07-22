@extends('layouts.app')

@section('content')
                    <x-page-panel title="Users">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Eggs</th>
                                <th>Editor</th>
                                <th>2FA/U2F</th>
                                <th>Last active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></td>
                                    <td>{{ $user->projects()->count() }}</td>
                                    <td>{{ $user->editor }}</td>
                                    <td>{!! $user->google2fa_enabled ? '<span class="u2f">2FA</span>' : ''  !!} {!! $user->webauthnKeys->isEmpty() ? '' : '<span class="u2f">U2F</span>' !!}</td>
                                    <td>{{ $user->projects()->count() > 0 ? $user->projects->last()->updated_at : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No public users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $users->links() }}
                </x-page-panel>
@endsection
