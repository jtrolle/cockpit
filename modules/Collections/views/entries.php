
<style>
@if($collection['color'])
.app-header { border-top: 8px {{ $collection['color'] }} solid; }
@endif
</style>

<script>

App.Utils.renderer.collectionlink = function(v) {

    if (Array.isArray(v)) {

        var vals = [];

        v.forEach(function(val) {
            vals.push(val && val.display ? val.display: App.Utils.renderer.default(val));
        });

        if (vals.length > 1) {
            return '<span class="uk-badge" title="'+vals.join(', ')+'" data-uk-tooltip>'+vals.length+'</span>';
        }

        return vals[0];
    }

    return v && v.display ? v.display : App.Utils.renderer.default(v);
};

function CollectionHasFieldAccess(field) {

    var acl = field.acl || [];

    if (field.name == '_modified' ||
        App.$data.user.group == 'admin' ||
        !acl ||
        (Array.isArray(acl) && !acl.length) ||
        acl.indexOf(App.$data.user.group) > -1 ||
        acl.indexOf(App.$data.user._id) > -1
    ) { return true; }

    return false;
}

</script>


<script type="riot/tag" src="@base('collections:assets/entries-batchedit.tag')"></script>

<div>

    <ul class="uk-breadcrumb">
        <li><a href="@route('/collections')">@lang('Collections')</a></li>
        <li class="uk-active" data-uk-dropdown="mode:'hover', delay:300">

            <a><i class="uk-icon-bars"></i> {{ htmlspecialchars(@$collection['label'] ? $collection['label']:$collection['name']) }}</a>

            @if($app->module('collections')->hasaccess($collection['name'], 'collection_edit'))
            <div class="uk-dropdown">
                <ul class="uk-nav uk-nav-dropdown">
                    <li class="uk-nav-header">@lang('Actions')</li>
                    <li><a href="@route('/collections/collection/'.$collection['name'])">@lang('Edit')</a></li>
                    <li class="uk-nav-divider"></li>
                    <li class="uk-text-truncate"><a href="@route('/collections/export/'.$collection['name'])" download="{{ $collection['name'] }}.collection.json">@lang('Export entries')</a></li>
                    <li class="uk-text-truncate"><a href="@route('/collections/import/collection/'.$collection['name'])">@lang('Import entries')</a></li>
                </ul>
            </div>
            @endif

        </li>
    </ul>

</div>

@render('collections:views/partials/entries'.($collection['sortable'] ? '.sortable':'').'.php', compact('collection'))
