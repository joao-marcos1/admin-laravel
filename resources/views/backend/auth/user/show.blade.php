@extends('backend.layouts.app')

@section('content')
    <x-backend.card>
        <x-slot name="header">
            {{ __('View User') }}
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.auth.user.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">
            <table class="table table-hover">
                <tr>
                    <th>@lang('Avatar')</th>
                    <td><img src="{{ $user->avatar }}" class="user-profile-image" /></td>
                </tr>

                <tr>
                    <th>@lang('Name')</th>
                    <td>{{ $user->name }}</td>
                </tr>

                <tr>
                    <th>@lang('E-mail Address')</th>
                    <td>{{ $user->email }}</td>
                </tr>

                <tr>
                    <th>@lang('Status')</th>
                    <td>@include('backend.auth.user.includes.status', ['user' => $user])</td>
                </tr>

                <tr>
                    <th>@lang('Verified')</th>
                    {{-- TODO: get model to be user by modifying livewire tables --}}
                    <td>@include('backend.auth.user.includes.verified', ['model' => $user])</td>
                </tr>

                <tr>
                    <th>@lang('Timezone')</th>
                    <td>{{ $user->timezone }}</td>
                </tr>

                <tr>
                    <th>@lang('Last Login At')</th>
                    <td>
                        @if($user->last_login_at)
                            {{ timezone()->convertToLocal($user->last_login_at) }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>@lang('Last Known IP Address')</th>
                    <td>{{ $user->last_login_ip ?? 'N/A' }}</td>
                </tr>
            </table>
        </x-slot>

        <x-slot name="footer">
            <small class="float-right text-muted">
                <strong>@lang('Created At'):</strong> {{ timezone()->convertToLocal($user->created_at) }} ({{ $user->created_at->diffForHumans() }}),
                <strong>@lang('Last Updated'):</strong> {{ timezone()->convertToLocal($user->updated_at) }} ({{ $user->updated_at->diffForHumans() }})

                @if($user->trashed())
                    <strong>@lang('Deleted At'):</strong> {{ timezone()->convertToLocal($user->deleted_at) }} ({{ $user->deleted_at->diffForHumans() }})
                @endif
            </small>
        </x-slot>
    </x-backend.card>
@endsection
