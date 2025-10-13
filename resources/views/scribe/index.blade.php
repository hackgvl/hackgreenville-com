<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>HackGreenville API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
                    body .content .python-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "{{ config("app.url") }}";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.2.1.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.2.1.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;,&quot;python&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
            <img src="/img/logo-v2.png" alt="logo" class="logo" style="padding-top: 10px;" width="100%"/>
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                            <button type="button" class="lang-button" data-language-name="python">python</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v0-events">
                                <a href="#endpoints-GETapi-v0-events">Events API v0</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v0-orgs">
                                <a href="#endpoints-GETapi-v0-orgs">Organizations API v0</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-events">
                                <a href="#endpoints-GETapi-v1-events">Events API v1</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-organizations">
                                <a href="#endpoints-GETapi-v1-organizations">Organizations API v1</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li></li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>Documentation for the HackGreenville API. This API provides access to data stored in the HackGreenville database, such as events, organizations and more.</p>
<aside>
    <strong>Base URL</strong>: <code>{{ config("app.url") }}</code>
</aside>
<p>This documentation aims to provide all the information you need to work with our API. <aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).  You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside></p>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-v0-events">Events API v0</h2>

<p>
</p>

<p>This API provides access to event data stored in the HackGreenville database.</p>

<span id="example-requests-GETapi-v0-events">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v0/events?start_date=2025-01-01&amp;end_date=2100-12-31" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v0/events"
);

const params = {
    "start_date": "2025-01-01",
    "end_date": "2100-12-31",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = '{{ config("app.url") }}/api/v0/events'
params = {
  'start_date': '2025-01-01',
  'end_date': '2100-12-31',
}
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v0-events">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;event_name&quot;: &quot;Dolores dolorum amet iste laborum eius est dolor.&quot;,
        &quot;group_name&quot;: &quot;Nash Corwin tech group!!!&quot;,
        &quot;group_url&quot;: &quot;quasi&quot;,
        &quot;url&quot;: &quot;http://www.huels.org/dignissimos-error-sit-labore-quos.html&quot;,
        &quot;time&quot;: &quot;2025-01-01T17:00:00.000000Z&quot;,
        &quot;tags&quot;: &quot;&quot;,
        &quot;status&quot;: &quot;past&quot;,
        &quot;rsvp_count&quot;: 97,
        &quot;description&quot;: &quot;Consequatur debitis et id. Qui id totam temporibus quia ipsam.&quot;,
        &quot;uuid&quot;: &quot;71edad68-e2ce-3042-9ff4-fd7f82df6cd1&quot;,
        &quot;data_as_of&quot;: &quot;2025-01-01T12:00:00.000000Z&quot;,
        &quot;service_id&quot;: &quot;9&quot;,
        &quot;service&quot;: &quot;eventbrite&quot;,
        &quot;venue&quot;: {
            &quot;name&quot;: &quot;est nostrum et voluptas consequatur&quot;,
            &quot;address&quot;: &quot;5090 Agustin Plaza\nThielfort, VA 23923&quot;,
            &quot;city&quot;: &quot;Estellehaven&quot;,
            &quot;state&quot;: &quot;VA&quot;,
            &quot;zip&quot;: &quot;37540&quot;,
            &quot;country&quot;: &quot;KP&quot;,
            &quot;lat&quot;: &quot;42.934149&quot;,
            &quot;lon&quot;: &quot;61.623526&quot;
        },
        &quot;created_at&quot;: &quot;2025-01-01T17:00:00.000000Z&quot;,
        &quot;is_paid&quot;: null
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v0-events" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v0-events"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v0-events"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v0-events" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v0-events">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v0-events" data-method="GET"
      data-path="api/v0/events"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v0-events', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v0-events"
                    onclick="tryItOut('GETapi-v0-events');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v0-events"
                    onclick="cancelTryOut('GETapi-v0-events');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v0-events"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v0/events</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v0-events"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v0-events"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="GETapi-v0-events"
               value="2025-01-01"
               data-component="query">
    <br>
<p>The start date for events filtering (inclusive). Must be a valid date in the format <code>Y-m-d</code>. Must be a date before or equal to <code>end_date</code>. Example: <code>2025-01-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>end_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="end_date"                data-endpoint="GETapi-v0-events"
               value="2100-12-31"
               data-component="query">
    <br>
<p>The end date for events filtering (inclusive). Must be a valid date. Must be a valid date in the format <code>Y-m-d</code>. Must be a date after or equal to <code>start_date</code>. Example: <code>2100-12-31</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tags</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tags"                data-endpoint="GETapi-v0-events"
               value=""
               data-component="query">
    <br>
<p>Filter events by organization tag ID.</p>
            </div>
                </form>

                    <h2 id="endpoints-GETapi-v0-orgs">Organizations API v0</h2>

<p>
</p>

<p>This API provides access to organization data stored in the HackGreenville database.</p>

<span id="example-requests-GETapi-v0-orgs">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v0/orgs" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v0/orgs"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = '{{ config("app.url") }}/api/v0/orgs'
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v0-orgs">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;title&quot;: &quot;minus&quot;,
        &quot;path&quot;: &quot;http://reichel.info/&quot;,
        &quot;changed&quot;: &quot;2025-01-01T17:00:00.000000Z&quot;,
        &quot;field_city&quot;: &quot;Lake Robynland&quot;,
        &quot;field_event_service&quot;: null,
        &quot;field_events_api_key&quot;: null,
        &quot;field_focus_area&quot;: &quot;fugit&quot;,
        &quot;field_homepage&quot;: &quot;dolores&quot;,
        &quot;field_event_calendar_homepage&quot;: &quot;https://www.lakin.com/veniam-sed-fuga-aspernatur-natus-earum&quot;,
        &quot;field_primary_contact_person&quot;: &quot;facilis&quot;,
        &quot;field_org_status&quot;: &quot;active&quot;,
        &quot;field_organization_type&quot;: &quot;perferendis&quot;,
        &quot;field_year_established&quot;: 2025,
        &quot;field_org_tags&quot;: &quot;&quot;,
        &quot;uuid&quot;: 123
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v0-orgs" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v0-orgs"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v0-orgs"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v0-orgs" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v0-orgs">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v0-orgs" data-method="GET"
      data-path="api/v0/orgs"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v0-orgs', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v0-orgs"
                    onclick="tryItOut('GETapi-v0-orgs');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v0-orgs"
                    onclick="cancelTryOut('GETapi-v0-orgs');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v0-orgs"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v0/orgs</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v0-orgs"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v0-orgs"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tags</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tags"                data-endpoint="GETapi-v0-orgs"
               value=""
               data-component="query">
    <br>
<p>Filter organizations by organization tag ID.</p>
            </div>
                </form>

                    <h2 id="endpoints-GETapi-v1-events">Events API v1</h2>

<p>
</p>

<p>This API provides access to event data stored in the HackGreenville database.</p>

<span id="example-requests-GETapi-v1-events">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v1/events?per_page=50&amp;page=1&amp;start_date=2025-01-01&amp;end_date=2100-12-31&amp;tags[]=17&amp;sort_by=event_name&amp;sort_direction=asc" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/events"
);

const params = {
    "per_page": "50",
    "page": "1",
    "start_date": "2025-01-01",
    "end_date": "2100-12-31",
    "tags[0]": "17",
    "sort_by": "event_name",
    "sort_direction": "asc",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = '{{ config("app.url") }}/api/v1/events'
params = {
  'per_page': '50',
  'page': '1',
  'start_date': '2025-01-01',
  'end_date': '2100-12-31',
  'tags[0]': '17',
  'sort_by': 'event_name',
  'sort_direction': 'asc',
}
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-events">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;71edad68-e2ce-3042-9ff4-fd7f82df6cd1&quot;,
            &quot;name&quot;: &quot;Dolores dolorum amet iste laborum eius est dolor.&quot;,
            &quot;description&quot;: &quot;Consequatur debitis et id. Qui id totam temporibus quia ipsam.&quot;,
            &quot;url&quot;: &quot;http://www.huels.org/dignissimos-error-sit-labore-quos.html&quot;,
            &quot;starts_at&quot;: &quot;2025-01-01T17:00:00.000000Z&quot;,
            &quot;ends_at&quot;: &quot;2025-01-01T19:00:00.000000Z&quot;,
            &quot;rsvp_count&quot;: 97,
            &quot;status&quot;: &quot;past&quot;,
            &quot;is_paid&quot;: null,
            &quot;organization&quot;: {
                &quot;id&quot;: 123,
                &quot;name&quot;: &quot;Nash Corwin tech group!!!&quot;,
                &quot;url&quot;: &quot;quasi&quot;,
                &quot;tags&quot;: []
            },
            &quot;venue&quot;: {
                &quot;name&quot;: &quot;est nostrum et voluptas consequatur&quot;,
                &quot;address&quot;: &quot;5090 Agustin Plaza\nThielfort, VA 23923&quot;,
                &quot;city&quot;: &quot;Estellehaven&quot;,
                &quot;state&quot;: {
                    &quot;code&quot;: &quot;VA&quot;,
                    &quot;name&quot;: &quot;VA&quot;
                },
                &quot;zipcode&quot;: &quot;37540&quot;,
                &quot;country&quot;: &quot;KP&quot;,
                &quot;location&quot;: {
                    &quot;latitude&quot;: &quot;42.934149&quot;,
                    &quot;longitude&quot;: &quot;61.623526&quot;
                }
            },
            &quot;service&quot;: {
                &quot;name&quot;: &quot;eventbrite&quot;,
                &quot;id&quot;: &quot;9&quot;
            },
            &quot;created_at&quot;: &quot;2025-01-01T17:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-01T17:00:00.000000Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-events" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-events"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-events"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-events" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-events">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-events" data-method="GET"
      data-path="api/v1/events"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-events', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-events"
                    onclick="tryItOut('GETapi-v1-events');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-events"
                    onclick="cancelTryOut('GETapi-v1-events');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-events"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/events</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-events"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-events"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-events"
               value="50"
               data-component="query">
    <br>
<p>The number of items to show per page. Must be at least 1. Must not be greater than 100. Example: <code>50</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-events"
               value="1"
               data-component="query">
    <br>
<p>The current page of items to display. Must be at least 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="GETapi-v1-events"
               value="2025-01-01"
               data-component="query">
    <br>
<p>The start date for events filtering (inclusive). Must be a valid date in the format <code>Y-m-d</code>. Must be a date before or equal to <code>end_date</code>. Example: <code>2025-01-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>end_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="end_date"                data-endpoint="GETapi-v1-events"
               value="2100-12-31"
               data-component="query">
    <br>
<p>The end date for events filtering (inclusive). Must be a valid date in the format <code>Y-m-d</code>. Must be a date after or equal to <code>start_date</code>. Example: <code>2100-12-31</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tags</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tags[0]"                data-endpoint="GETapi-v1-events"
               data-component="query">
        <input type="number" style="display: none"
               name="tags[1]"                data-endpoint="GETapi-v1-events"
               data-component="query">
    <br>
<p>The <code>id</code> of an existing record in the tags table.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-v1-events"
               value=""
               data-component="query">
    <br>
<p>Filter events by event name (the &quot;event_name&quot; property). Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>org_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="org_name"                data-endpoint="GETapi-v1-events"
               value=""
               data-component="query">
    <br>
<p>The name of the organization associated with the event (the &quot;group_name&quot; property). Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>service</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="service"                data-endpoint="GETapi-v1-events"
               value=""
               data-component="query">
    <br>
<p>The service that imported the event (meetup_graphql, eventbrite, etc.). Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>is_paid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="is_paid"                data-endpoint="GETapi-v1-events"
               value=""
               data-component="query">
    <br>
<p>Filter events that require payment (null means we currently cannot determine if event is paid).</p>
Must be one of:
<ul style="list-style-type: square;"><li><code>null</code></li> <li><code>true</code></li> <li><code>false</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>min_rsvp</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="min_rsvp"                data-endpoint="GETapi-v1-events"
               value=""
               data-component="query">
    <br>
<p>Must be at least 0.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>max_rsvp</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="max_rsvp"                data-endpoint="GETapi-v1-events"
               value=""
               data-component="query">
    <br>
<p>Must be at least 0.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>venue_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="venue_city"                data-endpoint="GETapi-v1-events"
               value=""
               data-component="query">
    <br>
<p>Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>venue_state</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="venue_state"                data-endpoint="GETapi-v1-events"
               value=""
               data-component="query">
    <br>
<p>Must be 2 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort_by</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sort_by"                data-endpoint="GETapi-v1-events"
               value="event_name"
               data-component="query">
    <br>
<p>Example: <code>event_name</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active_at</code></li> <li><code>event_name</code></li> <li><code>group_name</code></li> <li><code>rsvp_count</code></li> <li><code>created_at</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort_direction</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sort_direction"                data-endpoint="GETapi-v1-events"
               value="asc"
               data-component="query">
    <br>
<p>Example: <code>asc</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>asc</code></li> <li><code>desc</code></li></ul>
            </div>
                </form>

                    <h2 id="endpoints-GETapi-v1-organizations">Organizations API v1</h2>

<p>
</p>

<p>This API provides access to organization data stored in the HackGreenville database.</p>

<span id="example-requests-GETapi-v1-organizations">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "{{ config("app.url") }}/api/v1/organizations?per_page=50&amp;page=1&amp;sort_by=title&amp;sort_direction=asc" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "{{ config("app.url") }}/api/v1/organizations"
);

const params = {
    "per_page": "50",
    "page": "1",
    "sort_by": "title",
    "sort_direction": "asc",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = '{{ config("app.url") }}/api/v1/organizations'
params = {
  'per_page': '50',
  'page': '1',
  'sort_by': 'title',
  'sort_direction': 'asc',
}
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-organizations">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 123,
            &quot;title&quot;: &quot;minus&quot;,
            &quot;path&quot;: &quot;http://reichel.info/&quot;,
            &quot;city&quot;: &quot;Lake Robynland&quot;,
            &quot;service&quot;: null,
            &quot;service_api_key&quot;: null,
            &quot;focus_area&quot;: &quot;fugit&quot;,
            &quot;website_url&quot;: &quot;dolores&quot;,
            &quot;event_calendar_url&quot;: &quot;https://www.lakin.com/veniam-sed-fuga-aspernatur-natus-earum&quot;,
            &quot;primary_contact&quot;: &quot;facilis&quot;,
            &quot;status&quot;: &quot;active&quot;,
            &quot;organization_type&quot;: &quot;perferendis&quot;,
            &quot;established_year&quot;: 2025,
            &quot;tags&quot;: [],
            &quot;created_at&quot;: &quot;2025-01-01T17:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-01T17:00:00.000000Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-organizations" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-organizations"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-organizations"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-organizations" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-organizations">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-organizations" data-method="GET"
      data-path="api/v1/organizations"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-organizations', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-organizations"
                    onclick="tryItOut('GETapi-v1-organizations');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-organizations"
                    onclick="cancelTryOut('GETapi-v1-organizations');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-organizations"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/organizations</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-organizations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-organizations"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-organizations"
               value="50"
               data-component="query">
    <br>
<p>The number of items to show per page. Must be at least 1. Must not be greater than 100. Example: <code>50</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-organizations"
               value="1"
               data-component="query">
    <br>
<p>The current page of items to display. Must be at least 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tags</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tags[0]"                data-endpoint="GETapi-v1-organizations"
               data-component="query">
        <input type="number" style="display: none"
               name="tags[1]"                data-endpoint="GETapi-v1-organizations"
               data-component="query">
    <br>
<p>Filter organizations by tag ID. The <code>id</code> of an existing record in the tags table.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="GETapi-v1-organizations"
               value=""
               data-component="query">
    <br>
<p>Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="GETapi-v1-organizations"
               value=""
               data-component="query">
    <br>
<p>Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>focus_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="focus_area"                data-endpoint="GETapi-v1-organizations"
               value=""
               data-component="query">
    <br>
<p>The organization category (Entrpreneurship, Security, etc.). Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>organization_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="organization_type"                data-endpoint="GETapi-v1-organizations"
               value=""
               data-component="query">
    <br>
<p>The organization type (Meetup Groups, Code Schools, etc.). Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-organizations"
               value=""
               data-component="query">
    <br>
<p>The organization status (active, inactive, etc.). Must not be greater than 255 characters.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>established_from</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="established_from"                data-endpoint="GETapi-v1-organizations"
               value=""
               data-component="query">
    <br>
<p>The year the organization was established. Must be at least 1900. Must not be greater than 2025.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>established_to</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="established_to"                data-endpoint="GETapi-v1-organizations"
               value=""
               data-component="query">
    <br>
<p>The year the organization was dissolved. Must be at least 1900. Must not be greater than 2025.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort_by</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sort_by"                data-endpoint="GETapi-v1-organizations"
               value="title"
               data-component="query">
    <br>
<p>Example: <code>title</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>title</code></li> <li><code>city</code></li> <li><code>established_at</code></li> <li><code>updated_at</code></li> <li><code>created_at</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort_direction</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sort_direction"                data-endpoint="GETapi-v1-organizations"
               value="asc"
               data-component="query">
    <br>
<p>Example: <code>asc</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>asc</code></li> <li><code>desc</code></li></ul>
            </div>
                </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                                        <button type="button" class="lang-button" data-language-name="python">python</button>
                            </div>
            </div>
</div>
</body>
</html>
