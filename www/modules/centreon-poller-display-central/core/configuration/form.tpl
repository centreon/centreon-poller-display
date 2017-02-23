<form {$form.attributes}>
        <table class="formTable table">
            <tr class="ListHeader">
                <td class="FormHeader" colspan="2">
                    <h3>| {$form.header.title}</h3>
                </td>
            </tr>
            <tr class="list_one">
                <td class="FormRowField">
                    <img class="helpTooltip" name="poller_display">{$form.poller_display.label}
                </td>
                <td class="FormRowValue">
                    {$form.poller_display.html}
                </td>
            </tr>
        </table>

    {if !$valid}
    <div id="validForm">
        <p class="oreonbutton">{$form.submitC.html}{$form.submitA.html}</p>
    </div>
    {else}
    <div id="validForm" class="oreonbutton">
        <p>{$form.change.html}</p>
    </div>
    {/if}

{$form.hidden}
</form>
{$helptext}
