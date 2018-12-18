<div class="layui-collapse">
    <div class="layui-colla-item">
        <h2 class="layui-colla-title"><span class="layui-badge">备注：</span>验证规则说明</h2>
        <div class="layui-colla-content">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>规则</th>
                    <th>参数</th>
                    <th>描述</th>
                    <th>例子</th>
                </tr>
                </thead>
                <tbody>
                <tr><td><strong>required</strong></td>
                    <td>No</td>
                    <td>如果表单元素为空，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>matches</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值与参数中对应的表单字段的值不相等，返回 FALSE</td>
                    <td>matches[form_item]</td>
                </tr>
                <tr><td><strong>regex_match</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素不匹配正则表达式，返回 FALSE</td>
                    <td>regex_match[/regex/]</td>
                </tr>
                <tr><td><strong>differs</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值与参数中对应的表单字段的值相等，返回 FALSE</td>
                    <td>differs[form_item]</td>
                </tr>
                <tr><td><strong>is_unique</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值在指定的表和字段中并不唯一，返回 FALSE
                        注意：这个规则需要启用 <em>查询构造器</em></td>
                    <td>is_unique[table.field]</td>
                </tr>
                <tr><td><strong>min_length</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值的长度小于参数值，返回 FALSE</td>
                    <td>min_length[3]</td>
                </tr>
                <tr><td><strong>max_length</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值的长度大于参数值，返回 FALSE</td>
                    <td>max_length[12]</td>
                </tr>
                <tr><td><strong>exact_length</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值的长度不等于参数值，返回 FALSE</td>
                    <td>exact_length[8]</td>
                </tr>
                <tr><td><strong>greater_than</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值小于或等于参数值或非数字，返回 FALSE</td>
                    <td>greater_than[8]</td>
                </tr>
                <tr><td><strong>greater_than_equal_to</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值小于参数值或非数字，返回 FALSE</td>
                    <td>greater_than_equal_to[8]</td>
                </tr>
                <tr><td><strong>less_than</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值大于或等于参数值或非数字，返回 FALSE</td>
                    <td>less_than[8]</td>
                </tr>
                <tr><td><strong>less_than_equal_to</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值大于参数值或非数字，返回 FALSE</td>
                    <td>less_than_equal_to[8]</td>
                </tr>
                <tr><td><strong>in_list</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值不在规定的列表中，返回 FALSE</td>
                    <td>in_list[red,blue,green]</td>
                </tr>
                <tr><td><strong>alpha</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含除字母以外的其他字符，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>alpha_numeric</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含除字母和数字以外的其他字符，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>alpha_numeric_spaces</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含除字母、数字和空格以外的其他字符，返回 FALSE
                        应该在 trim 之后使用，避免首尾的空格</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>alpha_dash</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含除字母/数字/下划线/破折号以外的其他字符，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>numeric</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含除数字以外的字符，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>integer</strong></td>
                    <td>No</td>
                    <td>如果表单元素包含除整数以外的字符，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>decimal</strong></td>
                    <td>No</td>
                    <td>如果表单元素包含非十进制数字时，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>is_natural</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含了非自然数的其他数值 （不包括零），返回 FALSE
                        自然数形如：0、1、2、3 .... 等等。</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>is_natural_no_zero</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含了非自然数的其他数值 （包括零），返回 FALSE
                        非零的自然数：1、2、3 .... 等等。</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>valid_url</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含不合法的 URL，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>valid_email</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含不合法的 email 地址，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>valid_emails</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含不合法的 email 地址（地址之间用逗号分割），返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>valid_ip</strong></td>
                    <td>Yes</td>
                    <td>如果表单元素值不是一个合法的 IP 地址，返回 FALSE
                        通过可选参数 "ipv4" 或 "ipv6" 来指定 IP 地址格式。</td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td><strong>valid_base64</strong></td>
                    <td>No</td>
                    <td>如果表单元素值包含除了 base64 编码字符之外的其他字符，返回 FALSE</td>
                    <td>&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>