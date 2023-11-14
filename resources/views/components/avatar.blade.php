@props(['src'=>null])
<div {{$attributes->merge(['class'=>"flex-shrink-0 d-inline-flex align-items-center justify-content-center overflow-hidden rounded-circle"])}}>
 
    @if ($src)
        <img @class([ 'flex-shrink-0 object-fit-cover object-center rounded-circle', ])
            src="{{ $src }}"
            width="42"
            height="42"
        />
    @endif

    @if (!$src)
        <svg
            class="shrink-0 w-full h-full text-gray-300 bg-gray-100 dark:bg-gray-600"
            fill="currentColor"
            viewBox="0 0 24 24">
            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    @endif
</div>
