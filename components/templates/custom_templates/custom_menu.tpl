<ul class="nav navbar-nav navbar-right">
    {if $showThemesMenu}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
           aria-expanded="false">Themes
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" id="themes">
            {foreach from=$themes item=postfix key=name}
                <li><a href="#"{if $themePostfix == $postfix} style="font-weight: 800"{/if}>{$name}</a></li>
            {/foreach}
        </ul>
    </li>
    {/if}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            {$availableLangs[$currentLang]}
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" id="langs">
            {foreach item=lang key=key from=$availableLangs}
                {if $currentLang != $key}
                    <li><a href="#" data-lang="{$key}">{$lang}</a></li>
                {/if}
            {/foreach}
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
            Learn more
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown-header">PHP Generator Feature Demo</li>

            <li><a href="#" data-toggle="modal" data-target="#demo-about">About this demo</a></li>
            <li>
                <a href="http://www.sqlmaestro.com/products/mysql/phpgenerator/download/feature_demo_project/" title="Download project file for this demo" target="_blank">
                    Download project file for this demo
                </a>
            </li>

            <li role="separator" class="divider"></li>
            <li class="dropdown-header">Check out other PHP Generator demos</li>

            <li><a href="http://demo.sqlmaestro.com/nba/" title="Visit NBA Database Demo project">NBA Database Demo</a></li>
            <li><a href="http://demo.sqlmaestro.com/mysql_schema_browser/" title="Visit MySQL Schema Browser Demo project">MySQL Schema Browser Demo</a></li>

            <li role="separator" class="divider"></li>
            <li class="dropdown-header">Learn more about our products</li>

            <li>
                <a href="http://www.sqlmaestro.com/products/mysql/phpgenerator/" title="Visit PHP Generator for MySQL home page" target="_blank">
                    Try PHP Generator for free
                </a>
            </li>

            <li>
                <a href="http://www.sqlmaestro.com" title="Visit sqlmaestro.com" target="_blank">
                    SQL Maestro Group website
                </a>
            </li>
        </ul>
    </li>
</ul>
