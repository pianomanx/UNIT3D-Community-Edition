<section class="meta">
    @if ($meta?->backdrop)
        <img class="meta__backdrop" src="{{ tmdb_image('back_big', $meta->backdrop) }}" alt="" />
    @endif

    <a
        class="meta__title-link"
        href="{{ route('torrents.similar', ['category_id' => $category->id, 'tmdb' => $tmdb]) }}"
    >
        <h1 class="meta__title">
            {{ $meta->title ?? 'No Meta Found' }}
            ({{ substr($meta->release_date ?? '', 0, 4) ?? '' }})
        </h1>
    </a>
    <a
        class="meta__poster-link"
        href="{{ route('torrents.similar', ['category_id' => $category->id, 'tmdb' => $tmdb]) }}"
    >
        <img
            src="{{ $meta?->poster ? tmdb_image('poster_big', $meta->poster) : 'https://via.placeholder.com/400x600' }}"
            class="meta__poster"
        />
    </a>
    <div class="meta__actions">
        <a class="meta__dropdown-button" href="#">
            <i class="{{ config('other.font-awesome') }} fa-ellipsis-v"></i>
        </a>
        <ul class="meta__dropdown">
            <li>
                <a
                    href="{{
                        route('torrents.create', [
                            'category_id' => $category->id,
                            'title' => rawurlencode(($meta?->title ?? '') . ' ' . substr($meta->release_date ?? '', 0, 4) ?? ''),
                            'imdb' => $torrent->imdb ?? '',
                            'tmdb' => $meta?->id ?? '',
                            'mal' => $torrent->mal ?? '',
                            'tvdb' => $torrent->tvdb ?? '',
                            'igdb' => $torrent->igdb ?? '',
                        ])
                    }}"
                >
                    {{ __('common.upload') }}
                </a>
            </li>
            <li>
                <a
                    href="{{
                        route('requests.create', [
                            'category_id' => $category->id,
                            'title' => rawurlencode(($meta?->title ?? '') . ' ' . substr($meta->release_date ?? '', 0, 4) ?? ''),
                            'imdb' => $torrent->imdb ?? '',
                            'tmdb' => $meta?->id ?? '',
                            'mal' => $torrent->mal ?? '',
                            'tvdb' => $torrent->tvdb ?? '',
                            'igdb' => $torrent->igdb ?? '',
                        ])
                    }}"
                >
                    Request similar
                </a>
            </li>
            @if ($meta?->id)
                <li>
                    <form
                        action="{{ route('users.wishes.store', ['user' => auth()->user()]) }}"
                        method="post"
                    >
                        @csrf
                        <input type="hidden" name="meta" value="movie" />
                        <input type="hidden" name="movie_id" value="{{ $meta->id }}" />
                        <button
                            style="cursor: pointer"
                            title="Receive notifications every time a new torrent is uploaded."
                        >
                            Notify of New Uploads
                        </button>
                    </form>
                </li>
                <li>
                    <form
                        action="{{ route('torrents.similar.update', ['category' => $category, 'tmdbId' => $meta->id]) }}"
                        method="post"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            @if (cache()->has('tmdb-movie-scraper:' . $meta->id) && ! auth()->user()->group->is_modo)
                                disabled
                                title="This item was recently updated. Try again tomorrow."
                            @endif
                            style="cursor: pointer"
                        >
                            Update Metadata
                        </button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
    <ul class="meta__ids">
        @if ($meta->id ?? 0 > 0)
            <li class="meta__tmdb">
                <a
                    class="meta-id-tag"
                    href="https://www.themoviedb.org/movie/{{ $meta->id }}"
                    title="The Movie Database: {{ $meta->id }}"
                    target="_blank"
                >
                    <img src="{{ url('/img/meta/tmdb.svg') }}" />
                </a>
            </li>
        @endif

        @if ($meta->imdb_id ?? 0 > 0)
            <li class="meta__imdb">
                <a
                    class="meta-id-tag"
                    href="https://www.imdb.com/title/tt{{ \str_pad((string) $meta->imdb_id, \max(\strlen((string) $meta->imdb_id), 7), '0', STR_PAD_LEFT) }}"
                    title="Internet Movie Database: {{ \str_pad((string) $meta->imdb_id, \max(\strlen((string) $meta->imdb_id), 7), '0', STR_PAD_LEFT) }}"
                    target="_blank"
                >
                    <img src="{{ url('/img/meta/imdb.svg') }}" />
                </a>
            </li>
        @endif

        @if ($torrent->mal ?? 0 > 0)
            <li class="meta__mal">
                <a
                    class="meta-id-tag"
                    href="https://myanimelist.net/anime/{{ $torrent->mal }}"
                    title="My Anime List: {{ $torrent->mal }}"
                    target="_blank"
                >
                    <img src="{{ url('/img/meta/mal.svg') }}" />
                </a>
            </li>
        @endif

        @if ($torrent->tvdb ?? 0 > 0)
            <li class="meta__tvdb">
                <a
                    class="meta-id-tag"
                    href="https://www.thetvdb.com/?tab=series&id={{ $torrent->tvdb }}"
                    title="The TV Database: {{ $torrent->tvdb }}"
                    target="_blank"
                >
                    <img src="{{ url('/img/meta/tvdb.svg') }}" />
                </a>
            </li>
        @endif

        @if ($meta->id ?? 0 > 0)
            <li class="meta__rotten">
                <a
                    class="meta-id-tag"
                    href="https://html.duckduckgo.com/html/?q=\{{ $meta->title ?? '' }}  ({{ substr($meta->release_date ?? '', 0, 4) ?? '' }})+site%3Arottentomatoes.com"
                    title="Rotten Tomatoes: {{ $meta->title ?? '' }}  ({{ substr($meta->release_date ?? '', 0, 4) ?? '' }})"
                    target="_blank"
                    rel="noreferrer"
                >
                    <i
                        class="fad fa-tomato"
                        style="
                            --fa-secondary-opacity: 1;
                            --fa-primary-color: green;
                            --fa-secondary-color: red;
                        "
                    ></i>
                </a>
            </li>
        @endif

        @if ($meta->imdb_id ?? 0 > 0)
            <li class="meta__bluray">
                <a
                    class="meta-id-tag"
                    href="https://www.blu-ray.com/search/?quicksearch=1&quicksearch_keyword=tt{{ $meta->imdb_id ?? '' }}&section=theatrical"
                    title="Blu-ray: {{ $meta->title ?? '' }}  ({{ substr($meta->release_date ?? '', 0, 4) ?? '' }})"
                    target="_blank"
                >
                    <img class="" src="{{ url('/img/meta/bluray.svg') }}" style="width: 40px" />
                </a>
            </li>
        @endif
    </ul>
    <p class="meta__description">{{ $meta?->overview }}</p>
    <div class="meta__chips">
        <section class="meta__chip-container">
            <h2 class="meta__heading">Cast</h2>
            @foreach ($meta?->credits?->where('occupation_id', '=', App\Enums\Occupation::ACTOR->value)?->sortBy('order') ?? [] as $credit)
                <article class="meta-chip-wrapper">
                    <a
                        href="{{ route('mediahub.persons.show', ['id' => $credit->person->id, 'occupationId' => $credit->occupation_id]) }}"
                        class="meta-chip"
                    >
                        @if ($credit->person->still)
                            <img
                                class="meta-chip__image"
                                src="{{ tmdb_image('cast_face', $credit->person->still) }}"
                                alt=""
                            />
                        @else
                            <i
                                class="{{ config('other.font-awesome') }} fa-user meta-chip__icon"
                            ></i>
                        @endif
                        <h2 class="meta-chip__name">{{ $credit->person->name }}</h2>
                        <h3 class="meta-chip__value">{{ $credit->character }}</h3>
                    </a>
                </article>
            @endforeach
        </section>
        <section class="meta__chip-container" title="Crew">
            <h2 class="meta__heading">Crew</h2>
            @foreach ($meta?->credits?->where('occupation_id', '!=', App\Enums\Occupation::ACTOR->value)?->sortBy('occupation.position') ?? [] as $credit)
                <article class="meta-chip-wrapper">
                    <a
                        href="{{ route('mediahub.persons.show', ['id' => $credit->person->id, 'occupationId' => $credit->occupation_id]) }}"
                        class="meta-chip"
                    >
                        @if ($credit->person->still)
                            <img
                                class="meta-chip__image"
                                src="{{ tmdb_image('cast_face', $credit->person->still) }}"
                                alt=""
                            />
                        @else
                            <i
                                class="{{ config('other.font-awesome') }} fa-user meta-chip__icon"
                            ></i>
                        @endif
                        <h2 class="meta-chip__name">{{ $credit->occupation->name }}</h2>
                        <h3 class="meta-chip__value">{{ $credit->person->name }}</h3>
                    </a>
                </article>
            @endforeach
        </section>
        <section class="meta__chip-container">
            <h2 class="meta__heading">Extra Information</h2>
            <article class="meta-chip-wrapper meta-chip">
                <i class="{{ config('other.font-awesome') }} fa-star meta-chip__icon"></i>
                <h2 class="meta-chip__name">{{ __('torrent.rating') }}</h2>
                <h3 class="meta-chip__value">
                    {{ ($meta->vote_average ?? 0) * 10 }}% / {{ $meta->vote_count ?? 0 }}
                    {{ __('torrent.votes') }}
                </h3>
            </article>
            @if ($meta?->trailer)
                <article class="meta__trailer show-trailer">
                    <a class="meta-chip" href="#">
                        <i
                            class="{{ config('other.font-awesome') }} fa-external-link meta-chip__icon"
                        ></i>
                        <h2 class="meta-chip__name">Trailer</h2>
                        <h3 class="meta-chip__value">View</h3>
                    </a>
                </article>
            @endif

            <article class="meta__runtime">
                <a class="meta-chip" href="#">
                    <i class="{{ config('other.font-awesome') }} fa-clock meta-chip__icon"></i>
                    <h2 class="meta-chip__name">Runtime</h2>
                    <h3 class="meta-chip__value">{{ $meta->runtime ?? 0 }} Minutes</h3>
                </a>
            </article>
            @if ($meta?->genres?->isNotEmpty())
                <article class="meta__genres">
                    <a
                        class="meta-chip"
                        href="{{ route('torrents.index', ['view' => 'group', 'genreIds' => $meta->genres->pluck('id')->toArray()]) }}"
                    >
                        <i
                            class="{{ config('other.font-awesome') }} fa-theater-masks meta-chip__icon"
                        ></i>
                        <h2 class="meta-chip__name">Genres</h2>
                        <h3 class="meta-chip__value">
                            {{ $meta->genres->pluck('name')->join(' / ') }}
                        </h3>
                    </a>
                </article>
            @endif

            <article class="meta__language">
                <a
                    class="meta-chip"
                    href="{{ $meta?->original_language === null ? '#' : route('torrents.index', ['primaryLanguageNames' => [$meta->original_language]]) }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-language meta-chip__icon"></i>
                    <h2 class="meta-chip__name">Primary Language</h2>
                    <h3 class="meta-chip__value">
                        {{ $meta->original_language ?? __('common.unknown') }}
                    </h3>
                </a>
            </article>
            @foreach ($meta?->companies ?? [] as $company)
                <article class="meta__company">
                    <a
                        class="meta-chip"
                        href="{{ route('torrents.index', ['view' => 'group', 'companyId' => $company->id]) }}"
                    >
                        @if ($company->logo)
                            <img
                                class="meta-chip__image"
                                style="object-fit: scale-down"
                                src="{{ tmdb_image('logo_small', $company->logo) }}"
                                alt=""
                            />
                        @else
                            <i
                                class="{{ config('other.font-awesome') }} fa-camera-movie meta-chip__icon"
                            ></i>
                        @endif
                        <h2 class="meta-chip__name">Company</h2>
                        <h3 class="meta-chip__value">{{ $company->name }}</h3>
                    </a>
                </article>
            @endforeach

            @if (isset($torrent) && $torrent?->keywords?->isNotEmpty())
                <article class="meta__keywords">
                    <a
                        class="meta-chip"
                        href="{{ route('torrents.index', ['view' => 'group', 'keywords' => $torrent->keywords->pluck('name')->join(', ')]) }}"
                    >
                        <i class="{{ config('other.font-awesome') }} fa-tag meta-chip__icon"></i>
                        <h2 class="meta-chip__name">Keywords</h2>
                        <h3 class="meta-chip__value">
                            {{ $torrent->keywords->pluck('name')->join(', ') }}
                        </h3>
                    </a>
                </article>
            @endif
        </section>
    </div>
</section>

@if ($meta?->trailer)
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce() }}">
        document.getElementsByClassName('show-trailer')[0].addEventListener('click', (e) => {
            e.preventDefault();
            Swal.fire({
                showConfirmButton: false,
                showCloseButton: true,
                background: 'rgb(35,35,35)',
                width: 970,
                html: '<iframe width="930" height="523" src="https://www.youtube-nocookie.com/embed/{{ $meta->trailer }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
                title: '<i style="color: #a5a5a5;">{{ $meta->title }} Trailer</i>',
                text: '',
            });
        });
    </script>
@endif
