<div class="layout-selector">
    <ul>
        <li ng-repeat="layout in config.layouts">
            <a class="layout-btn {{layout.value}}"
               ng-class="{'active':isActive(layout.value)}"
               title="{{layout.label}}"
               ng-click="setLayout(layout.value)">{{layout.label}}</a>
        </li>
    </ul>
    <span class="title">{{getSelectedLabel()}}</span>
    <div class="fixed"></div>
</div>
<div class="layout-columns">

    <div class="blog-left-sidebar" ng-if="displayLeftSidebar()">
        <div class="column mp-layout-column" ng-init="type='sidebar'; subtype='left_side';"></div>
    </div>
    <div class="blog-content">
        <div class="column mp-layout-column" ng-init="type='content'; subtype='content';" ></div>
    </div>
    <div class="blog-right-sidebar"  ng-if="displayRightSidebar()">
        <div class="column mp-layout-column" ng-init="type='sidebar'; subtype='right_side';"></div>
    </div>

    <div class="fixed"></div>
</div>
<input name="{{name}}" type="hidden" value="{{value | json}}" />