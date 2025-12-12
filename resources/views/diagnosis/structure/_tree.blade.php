<ul style="list-style-type:none; margin:0 0 0 15px; padding:0;">
    @foreach($nodes as $node)
        <li style="margin:2px 0; font-size:13px;">
            @if($node['type'] === 'dir')
                <strong>{{ $node['name'] }}</strong>
                @if(!empty($node['children']))
                    @include('diagnosis.structure._tree', ['nodes' => $node['children']])
                @endif
            @else
                {{-- فایل --}}
                <a href="{{ route('diagnosis.file', ['path' => $node['path']]) }}">
                    {{ $node['name'] }}
                </a>
            @endif
        </li>
    @endforeach
</ul>
