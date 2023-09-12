@extends('layout.main')

@push('page-js-after')

    <script>
        var txt = '{"orderBy": "", "pageNo": 1, "pageSize": "100"}'
        console.log($.base64.encode(txt, "utf-8"))
        var parseData = $.base64.decode("lDaCCjSWSsmhHwc+i7yW1lEdjCg4PD+KuqgHQe7yC4ZktGY1JFPHXTP4N7Tsa6tMu3i/xe5JwgAh9vyT/ULmeF2Uqc/GOxWVH+WHlQgVik26+oBBJ8Tff7UEqOZov/980j/LN8uhN2hrVTfSAORu7zCld7ycLHnuEqvBHHsCxbxfvc63+BWE8H8cYHPVzOnKYfGKuekwL2XjEfSSO3hv58xezv/nULl24NqadaLAbC53TZCnRz77TotuiXkDKHLjeMkELcdAQqmmgsX1P4ihqRidUHA7AgYULpsdbvXY/x1qazjYWUBeOYd4es30iipXDqkCmbXXVKMA+vQXNs7gGclCcCNpLI3wr/9KVTRQIXWhjdvS3Fda2SXOk1yK5FDEQ5pkqbPj9fQPMjyCPxp2bT31LL+K0VwntCbZMd3CcEz2M4zvxGRldxnMUTFxq1t0u5sTl0rzyQ37T9xCHfXIlMck4wMuzb73S/LeqFD/7VkKQu0/tBkL1soR2mNLtbQopARQMY6P5iBxpFDsfOuVoRazU1eRJb0SAHl6YTXbZRoF+MOkF49OccY5dCy5KPk5CSkl35fF50P68/qw+bDZjoJ+FzJ0sjc72dko7FZ0WiN8bYBS5DE1+bG6pNYUWQh6CgP2iSUTyMtSmIHDSbmY7Ca5inpdGNrXu3wBYPw992qLqfDbAqepu28zlln0QvEOg3TlxQ9cO7CAMul2bie4ybpaFGKeDhn9nu1oE0/RYA4=")
        console.log(parseData)
    </script>

@endpush
