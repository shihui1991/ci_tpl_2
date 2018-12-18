<div class="layui-table-page">
    <?php echo $data['Links']; ?>
    <span class="layui-laypage-count">
        当前 <?php echo count($data['List']); ?> 条，
        每页 <?php echo $data['PerPage']; ?> 条，
        共 <?php echo number_format($data['Total']); ?> 条
    </span>
</div>