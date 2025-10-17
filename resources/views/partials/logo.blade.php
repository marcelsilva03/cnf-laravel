<?php if (isset($url)): ?>
    <a href="{{ $url }}">
        <img src="{{ asset('images/cnf_lg.png') }}" alt="CNF" width="{{ $width ?? '' }}" />
    </a>
<?php else: ?>
    <img src="{{ asset('images/cnf_lg.png') }}" alt="CNF" width="{{ $width ?? '' }}" />
<?php endif; ?>
