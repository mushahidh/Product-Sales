<ul class="user-menu">
    <li class="dropdown pull-right">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> <?= \Yii::$app->session['name'] ?> <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="/branch/app"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Profile</a></li>
            <li><a href="/branch?edit=1"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg>Branches</a></li>
            <li><a href="/notification?edit=1"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg>Notifications</a></li>
            <li><a href="/app?edit=1"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg>Utilities</a></li>
            <li><a href="/update?edit=1"><svg class="glyph stroked gear"><use xlink:href="#stroked-gear"></use></svg>Update Sajil</a></li>
            <li><a href="/site/logout"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Logout</a></li>
        </ul>
    </li>
</ul> 