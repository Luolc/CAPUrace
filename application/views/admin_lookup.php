<div class="indcontainer">
    <table class="table">
        <thead>
        <tr>
            <th>学校名称</th>
            <th>车协名称</th>
            <th>所在地区</th>
            <th>领队姓名</th>
            <th>电子邮箱</th>
            <th>手机号</th>
            <th>邮寄地址</th>
            <th>邮政编码</th>
            <th>费用合计</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$userinfo['school']?></td>
            <td><?=$userinfo['association_name']?></td>
            <td><?=$GLOBALS['PROVINCES_SHORT'][$userinfo['province']]?></td>
            <td><?=$userinfo['leader']?></td>
            <td><?=$userinfo['mail']?></td>
            <td><?=$userinfo['tel']?></td>
            <td><?=$userinfo['address']?></td>
            <td><?=$userinfo['zipcode']?></td>
            <td><?=$userinfo['bill']?></td>
        </tr>
        </tbody>
    </table>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>序号</th>
            <th>姓名</th>
            <th>性别</th>
            <th>手机号</th>
            <th>身份证号</th>
            <th>北大赛</th>
            <th>住宿</th>
            <th>5.14晚餐</th>
            <th>5.15午餐</th>
            <th>清真</th>
            <th>费用</th>
        </tr>
        </thead>
        <tbody class="ind-list">
        <?php $i = 1; ?>
        <?php foreach ($individual as $item): ?>
            <tr class="ind-item">
                <td class="order"><?=$i?></td>
                <td class="name"><?=$item['name']?></td>
                <td class="gender"><?=$GLOBALS['GENDER'][$item['gender']]?></td>
                <td class="tel"><?=$item['tel']?></td>
                <td class="id_card"><?=$item['id_card']?></td>
                <td class="race">
                    <?php
                    if($item['race']) {
                        echo  $GLOBALS['CAPURACE'][$item['race']];
                    }
                    if ($item['ifteam']) {
                        echo ' 团体赛 ';
                    } else if (!$item['race']) {
                        echo ' 不参加 ';
                    }
                    ?>
                </td>
                <td class="accommodation"><?=$GLOBALS['ACCOMMODATION'][$item['accommodation']]?></td>
                <td class="meal16"><?=$GLOBALS['JUDGE'][$item['dinner']]?></td>
                <td class="meal17"><?=$GLOBALS['JUDGE'][$item['lunch']]?></td>
                <td class="islam"><?=$GLOBALS['JUDGE'][$item['islam']]?></td>
                <td class="fee"><?=$item['fee']?></td>
            </tr>
            <?php
            $i++;
        endforeach;
        ?>
        </tbody>
    </table>
    <hr/>


    <table class="table table-hover">
        <thead>
        <tr>
            <th>序号</th>
            <th>第一棒</th>
            <th>第二棒</th>
            <th>第三棒</th>
            <th>第四棒</th>
        </tr>
        </thead>
        <tbody class="team-list">
        <?php $i = 1; ?>
        <?php foreach ($team as $item): ?>
            <tr class="team-item">
                <td><?=$i?></td>
                <td><?=$item['first']?></td>
                <td><?=$item['second']?></td>
                <td><?=$item['third']?></td>
                <td><?=$item['fourth']?></td>
            </tr>
            <?php
            $i++;
        endforeach;
        ?>
        </tbody>
    </table>
</div>