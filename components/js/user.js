$('a.expand-details').on("click", function() {
  setTimeout(highlightWinLossRows, 3000);
});



function scrollToBottom() {
    window.scrollTo(0, document.body.scrollHeight);
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function makePager(prevLinkURL, prevCaption, nextLinkURL, nextCaption) {
    var $result = $('<ul class="pager"></ul>');
    if (prevLinkURL) {
        $result.append('<li class="previous"><a href="' + prevLinkURL + '" title="Previous Page"><span aria-hidden="true">&larr;</span><span class="hidden-xs"> ' + prevCaption + '</span></a></li>')
    }
    if (nextLinkURL) {
        $result.append('<li class="next"><a href="' + nextLinkURL + '" title="Next Page"><span class="hidden-xs">' + nextCaption + ' </span><span aria-hidden="true">&rarr;</span></a></li>')
    }
    return $result.wrap('<nav></nav>');
}

function extractPageLink(direction) {
    var $items = $(".sidebar li:not(:empty):not(.sidebar-nav-head):not(.js-whatsnew-item)");
    var activeIndex = $items.index($(".sidebar li.active"));
    if (direction === 'backward') {
        activeIndex -= 1;
    }
    return $items.slice(activeIndex).filter(function (i, item) {
        return $(item).find('>a').length;
    }).first().find('a');
}

function buildLinkCaption($link) {
    return  $link.closest('li.sidebar-nav-head').find('span').first().text().trim() + '::' + $link.text().trim();
}


function handleThemeSelection() {
    $("#themes").on("click", "a", function (e) {
        setCookie('theme', $(this).text(), 360);
        location.reload();
        e.preventDefault();
    });
}

function closeCallback () {
    return true;
}

function showModal() {
    $('a[data-bootbox]').on("click", function() {
        var elementId = $(this).data("bootbox");
        var elementContent = $(elementId).html();
        bootbox.dialog({
            size: 'large',
            title: "",
            message: elementContent,
            backdrop: true,
            onEscape: closeCallback,
            buttons: {
                cancel: {
                    label: 'Close',
                    callback: closeCallback
                }              
            }
        });
    });
}

function handleLanguageSelection() {
     $("#langs").on("click", "a", function (e) {
         var query = jQuery.query;
         query = query.set('lang', $(this).data('lang'));
         window.location = query;
         e.preventDefault();
     });
}

function addPagerToDescritption() {
    var $descriptionBlock = $('div.description');
    if ($descriptionBlock.length === 0) {
        return;
    }
    var $prevLink = extractPageLink('backward');
    var $nextLink = extractPageLink('forward');
    var $pager = makePager(
        $prevLink.attr('href'), buildLinkCaption($prevLink),
        $nextLink.attr('href'), buildLinkCaption($nextLink)
    );
    $descriptionBlock.append($pager);
    setTimeout(function(){$pager.css('opacity', 1)}, 0);
}

function highlightCode() {
    $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
    });
}

function expandDetailedDescriptionWindow() {
    $('#detailedDescriptionModal > div.modal-dialog').addClass('modal-lg')
}

require(['jquery'], function () {
    $(function () {
        handleThemeSelection();
        handleLanguageSelection();
        addPagerToDescritption();
        expandDetailedDescriptionWindow();
        highlightCode();
        showModal();
    });
});