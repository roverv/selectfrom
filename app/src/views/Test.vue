<template>

  <div class="content">
    <h1>Glass_action</h1>
    <br>
    <br>
    <table id="table" cellspacing="0" class="nowrap checkable">

      <thead >
      <tr style="position:sticky;">
        <td><input type="checkbox" id="all-page" class="jsonly">

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;modify=1">Modify</a></td>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=id"><span
            title="mediumint(8) unsigned">id</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=id&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=invoice_id"><span
            title="mediumint(8) unsigned">invoice_id</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=invoice_id&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=sent_to"><span
            title="varchar(255)">sent_to</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=sent_to&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=sendtype"><span
            title="varchar(30)">sendtype</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=sendtype&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=status"><span
            title="varchar(30)">status</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=status&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=email_subject"><span
            title="varchar(255)">email_subject</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=email_subject&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=email_message"><span
            title="text">email_message</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=email_message&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=pdf_filename"><span
            title="varchar(150)">pdf_filename</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=pdf_filename&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=insertTS"><span
            title="datetime">insertTS</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=insertTS&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
        <th>

          <a href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=insertUser"><span
            title="mediumint(8) unsigned">insertUser</span></a><span class="column hidden"><a
          href="/adminer/?username=root&amp;db=rove&amp;select=invoice_status&amp;order%5B0%5D=insertUser&amp;desc%5B0%5D=1"
          title="Aflopend" class="text"> ↓</a><a href="#fieldset-search" title="Zoeken" class="text jsonly"> =</a>
</span></th>
      </tr>
      </thead>
      <tbody>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=1"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=1" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=1][id]" data-text="0">1</td>
        <td id="val[&amp;where%5Bid%5D=1][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=2">2</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=1][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=1][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=1][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=1][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=1][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=1][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=1][insertTS]" data-text="0">2015-07-28 11:35:44</td>
        <td id="val[&amp;where%5Bid%5D=1][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=2"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=2" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=2][id]" data-text="0">2</td>
        <td id="val[&amp;where%5Bid%5D=2][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=3">3</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=2][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=2][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=2][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=2][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=2][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=2][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=2][insertTS]" data-text="0">2015-07-28 13:01:39</td>
        <td id="val[&amp;where%5Bid%5D=2][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=3"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=3" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=3][id]" data-text="0">3</td>
        <td id="val[&amp;where%5Bid%5D=3][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=3">3</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=3][sent_to]" data-text="0"><a href="mailto:paul_561@hotmail.com">paul_561@hotmail.com</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=3][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=3][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=3][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=3][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=3][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=3][insertTS]" data-text="0">2015-07-28 13:08:37</td>
        <td id="val[&amp;where%5Bid%5D=3][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=4"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=4" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=4][id]" data-text="0">4</td>
        <td id="val[&amp;where%5Bid%5D=4][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=5">5</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=4][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=4][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=4][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=4][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=4][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=4][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=4][insertTS]" data-text="0">2015-07-30 17:47:31</td>
        <td id="val[&amp;where%5Bid%5D=4][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=5"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=5" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=5][id]" data-text="0">5</td>
        <td id="val[&amp;where%5Bid%5D=5][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=4">4</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=5][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=5][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=5][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=5][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=5][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=5][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=5][insertTS]" data-text="0">2015-07-30 17:47:35</td>
        <td id="val[&amp;where%5Bid%5D=5][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=6"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=6" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=6][id]" data-text="0">6</td>
        <td id="val[&amp;where%5Bid%5D=6][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=6">6</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=6][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=6][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=6][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=6][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=6][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=6][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=6][insertTS]" data-text="0">2015-07-30 17:51:56</td>
        <td id="val[&amp;where%5Bid%5D=6][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=7"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=7" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=7][id]" data-text="0">7</td>
        <td id="val[&amp;where%5Bid%5D=7][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=7">7</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=7][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=7][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=7][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=7][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=7][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=7][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=7][insertTS]" data-text="0">2015-07-30 18:59:05</td>
        <td id="val[&amp;where%5Bid%5D=7][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=8"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=8" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=8][id]" data-text="0">8</td>
        <td id="val[&amp;where%5Bid%5D=8][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=20">20</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=8][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=8][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=8][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=8][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=8][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=8][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=8][insertTS]" data-text="0">2015-07-30 19:07:11</td>
        <td id="val[&amp;where%5Bid%5D=8][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=9"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=9" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=9][id]" data-text="0">9</td>
        <td id="val[&amp;where%5Bid%5D=9][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=19">19</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=9][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=9][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=9][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=9][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=9][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=9][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=9][insertTS]" data-text="0">2015-07-30 19:07:13</td>
        <td id="val[&amp;where%5Bid%5D=9][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=10"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=10" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=10][id]" data-text="0">10</td>
        <td id="val[&amp;where%5Bid%5D=10][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=18">18</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=10][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=10][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=10][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=10][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=10][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=10][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=10][insertTS]" data-text="0">2015-07-30 19:07:16</td>
        <td id="val[&amp;where%5Bid%5D=10][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=11"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=11" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=11][id]" data-text="0">11</td>
        <td id="val[&amp;where%5Bid%5D=11][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=17">17</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=11][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=11][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=11][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=11][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=11][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=11][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=11][insertTS]" data-text="0">2015-07-30 19:07:18</td>
        <td id="val[&amp;where%5Bid%5D=11][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=12"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=12" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=12][id]" data-text="0">12</td>
        <td id="val[&amp;where%5Bid%5D=12][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=16">16</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=12][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=12][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=12][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=12][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=12][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=12][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=12][insertTS]" data-text="0">2015-07-30 19:07:20</td>
        <td id="val[&amp;where%5Bid%5D=12][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=13"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=13" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=13][id]" data-text="0">13</td>
        <td id="val[&amp;where%5Bid%5D=13][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=15">15</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=13][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=13][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=13][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=13][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=13][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=13][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=13][insertTS]" data-text="0">2015-07-30 19:07:23</td>
        <td id="val[&amp;where%5Bid%5D=13][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=14"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=14" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=14][id]" data-text="0">14</td>
        <td id="val[&amp;where%5Bid%5D=14][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=14">14</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=14][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=14][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=14][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=14][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=14][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=14][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=14][insertTS]" data-text="0">2015-07-30 19:07:26</td>
        <td id="val[&amp;where%5Bid%5D=14][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=15"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=15" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=15][id]" data-text="0">15</td>
        <td id="val[&amp;where%5Bid%5D=15][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=13">13</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=15][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=15][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=15][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=15][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=15][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=15][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=15][insertTS]" data-text="0">2015-07-30 19:07:28</td>
        <td id="val[&amp;where%5Bid%5D=15][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=16"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=16" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=16][id]" data-text="0">16</td>
        <td id="val[&amp;where%5Bid%5D=16][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=12">12</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=16][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=16][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=16][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=16][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=16][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=16][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=16][insertTS]" data-text="0">2015-07-30 19:07:31</td>
        <td id="val[&amp;where%5Bid%5D=16][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=17"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=17" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=17][id]" data-text="0">17</td>
        <td id="val[&amp;where%5Bid%5D=17][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=11">11</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=17][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=17][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=17][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=17][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=17][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=17][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=17][insertTS]" data-text="0">2015-07-30 19:07:33</td>
        <td id="val[&amp;where%5Bid%5D=17][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=18"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=18" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=18][id]" data-text="0">18</td>
        <td id="val[&amp;where%5Bid%5D=18][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=10">10</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=18][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=18][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=18][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=18][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=18][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=18][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=18][insertTS]" data-text="0">2015-07-30 19:07:35</td>
        <td id="val[&amp;where%5Bid%5D=18][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=19"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=19" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=19][id]" data-text="0">19</td>
        <td id="val[&amp;where%5Bid%5D=19][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=9">9</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=19][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=19][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=19][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=19][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=19][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=19][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=19][insertTS]" data-text="0">2015-07-30 19:07:37</td>
        <td id="val[&amp;where%5Bid%5D=19][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=20"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=20" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=20][id]" data-text="0">20</td>
        <td id="val[&amp;where%5Bid%5D=20][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=8">8</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=20][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=20][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=20][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=20][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=20][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=20][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=20][insertTS]" data-text="0">2015-07-30 19:07:39</td>
        <td id="val[&amp;where%5Bid%5D=20][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=21"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=21" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=21][id]" data-text="0">21</td>
        <td id="val[&amp;where%5Bid%5D=21][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=26">26</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=21][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=21][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=21][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=21][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=21][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=21][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=21][insertTS]" data-text="0">2015-08-01 14:52:56</td>
        <td id="val[&amp;where%5Bid%5D=21][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=22"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=22" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=22][id]" data-text="0">22</td>
        <td id="val[&amp;where%5Bid%5D=22][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=25">25</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=22][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=22][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=22][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=22][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=22][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=22][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=22][insertTS]" data-text="0">2015-08-01 14:52:59</td>
        <td id="val[&amp;where%5Bid%5D=22][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=23"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=23" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=23][id]" data-text="0">23</td>
        <td id="val[&amp;where%5Bid%5D=23][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=23">23</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=23][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=23][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=23][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=23][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=23][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=23][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=23][insertTS]" data-text="0">2015-08-01 14:53:01</td>
        <td id="val[&amp;where%5Bid%5D=23][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=24"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=24" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=24][id]" data-text="0">24</td>
        <td id="val[&amp;where%5Bid%5D=24][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=22">22</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=24][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=24][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=24][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=24][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=24][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=24][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=24][insertTS]" data-text="0">2015-08-01 14:53:03</td>
        <td id="val[&amp;where%5Bid%5D=24][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=25"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=25" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=25][id]" data-text="0">25</td>
        <td id="val[&amp;where%5Bid%5D=25][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=21">21</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=25][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=25][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=25][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=25][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=25][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=25][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=25][insertTS]" data-text="0">2015-08-01 14:53:05</td>
        <td id="val[&amp;where%5Bid%5D=25][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=26"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=26" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=26][id]" data-text="0">26</td>
        <td id="val[&amp;where%5Bid%5D=26][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=27">27</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=26][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=26][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=26][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=26][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=26][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=26][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=26][insertTS]" data-text="0">2015-08-01 15:01:34</td>
        <td id="val[&amp;where%5Bid%5D=26][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=27"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=27" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=27][id]" data-text="0">27</td>
        <td id="val[&amp;where%5Bid%5D=27][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=29">29</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=27][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=27][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=27][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=27][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=27][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=27][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=27][insertTS]" data-text="0">2015-08-03 07:28:21</td>
        <td id="val[&amp;where%5Bid%5D=27][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=28"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=28" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=28][id]" data-text="0">28</td>
        <td id="val[&amp;where%5Bid%5D=28][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=30">30</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=28][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=28][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=28][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=28][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=28][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=28][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=28][insertTS]" data-text="0">2015-08-03 07:29:31</td>
        <td id="val[&amp;where%5Bid%5D=28][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=29"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=29" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=29][id]" data-text="0">29</td>
        <td id="val[&amp;where%5Bid%5D=29][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=30">30</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=29][sent_to]" data-text="0"><a href="mailto:josenchristel@ziggo.nl">josenchristel@ziggo.nl</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=29][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=29][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=29][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=29][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=29][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=29][insertTS]" data-text="0">2015-08-03 07:29:50</td>
        <td id="val[&amp;where%5Bid%5D=29][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=30"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=30" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=30][id]" data-text="0">30</td>
        <td id="val[&amp;where%5Bid%5D=30][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=31">31</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=30][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=30][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=30][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=30][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=30][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=30][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=30][insertTS]" data-text="0">2015-08-03 07:31:25</td>
        <td id="val[&amp;where%5Bid%5D=30][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=31"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=31" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=31][id]" data-text="0">31</td>
        <td id="val[&amp;where%5Bid%5D=31][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=31">31</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=31][sent_to]" data-text="0"><a href="mailto:Noortje@van-Puijenbroek.nl">Noortje@van-Puijenbroek.nl</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=31][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=31][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=31][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=31][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=31][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=31][insertTS]" data-text="0">2015-08-03 07:32:03</td>
        <td id="val[&amp;where%5Bid%5D=31][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=32"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=32" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=32][id]" data-text="0">32</td>
        <td id="val[&amp;where%5Bid%5D=32][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=32">32</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=32][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=32][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=32][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=32][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=32][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=32][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=32][insertTS]" data-text="0">2015-08-03 07:35:20</td>
        <td id="val[&amp;where%5Bid%5D=32][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=33"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=33" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=33][id]" data-text="0">33</td>
        <td id="val[&amp;where%5Bid%5D=33][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=32">32</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=33][sent_to]" data-text="0"><a href="mailto:larsvanhoof@hotmail.com">larsvanhoof@hotmail.com</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=33][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=33][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=33][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=33][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=33][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=33][insertTS]" data-text="0">2015-08-03 07:35:25</td>
        <td id="val[&amp;where%5Bid%5D=33][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=34"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=34" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=34][id]" data-text="0">34</td>
        <td id="val[&amp;where%5Bid%5D=34][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=33">33</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=34][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=34][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=34][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=34][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=34][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=34][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=34][insertTS]" data-text="0">2015-08-03 07:37:17</td>
        <td id="val[&amp;where%5Bid%5D=34][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=35"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=35" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=35][id]" data-text="0">35</td>
        <td id="val[&amp;where%5Bid%5D=35][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=33">33</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=35][sent_to]" data-text="0"><a href="mailto:invoice@willemsbaling.eu">invoice@willemsbaling.eu</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=35][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=35][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=35][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=35][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=35][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=35][insertTS]" data-text="0">2015-08-03 07:37:22</td>
        <td id="val[&amp;where%5Bid%5D=35][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=36"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=36" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=36][id]" data-text="0">36</td>
        <td id="val[&amp;where%5Bid%5D=36][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=34">34</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=36][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=36][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=36][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=36][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=36][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=36][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=36][insertTS]" data-text="0">2015-08-03 07:38:29</td>
        <td id="val[&amp;where%5Bid%5D=36][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=37"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=37" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=37][id]" data-text="0">37</td>
        <td id="val[&amp;where%5Bid%5D=37][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=34">34</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=37][sent_to]" data-text="0"><a href="mailto:info@schotshoveniersbedrijf.nl">info@schotshoveniersbedrijf.nl</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=37][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=37][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=37][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=37][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=37][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=37][insertTS]" data-text="0">2015-08-03 07:38:37</td>
        <td id="val[&amp;where%5Bid%5D=37][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=38"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=38" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=38][id]" data-text="0">38</td>
        <td id="val[&amp;where%5Bid%5D=38][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=35">35</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=38][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=38][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=38][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=38][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=38][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=38][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=38][insertTS]" data-text="0">2015-08-03 07:39:55</td>
        <td id="val[&amp;where%5Bid%5D=38][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=39"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=39" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=39][id]" data-text="0">39</td>
        <td id="val[&amp;where%5Bid%5D=39][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=35">35</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=39][sent_to]" data-text="0"><a href="mailto:n.tops@schippers.eu">n.tops@schippers.eu</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=39][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=39][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=39][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=39][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=39][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=39][insertTS]" data-text="0">2015-08-03 07:40:03</td>
        <td id="val[&amp;where%5Bid%5D=39][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=40"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=40" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=40][id]" data-text="0">40</td>
        <td id="val[&amp;where%5Bid%5D=40][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=36">36</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=40][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=40][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=40][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=40][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=40][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=40][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=40][insertTS]" data-text="0">2015-08-03 07:40:52</td>
        <td id="val[&amp;where%5Bid%5D=40][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=41"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=41" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=41][id]" data-text="0">41</td>
        <td id="val[&amp;where%5Bid%5D=41][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=36">36</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=41][sent_to]" data-text="0"><a
          href="mailto:lintsi@chello.nl">lintsi@chello.nl</a></td>
        <td id="val[&amp;where%5Bid%5D=41][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=41][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=41][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=41][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=41][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=41][insertTS]" data-text="0">2015-08-03 07:40:55</td>
        <td id="val[&amp;where%5Bid%5D=41][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=42"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=42" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=42][id]" data-text="0">42</td>
        <td id="val[&amp;where%5Bid%5D=42][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=37">37</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=42][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=42][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=42][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=42][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=42][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=42][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=42][insertTS]" data-text="0">2015-08-03 07:43:58</td>
        <td id="val[&amp;where%5Bid%5D=42][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=43"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=43" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=43][id]" data-text="0">43</td>
        <td id="val[&amp;where%5Bid%5D=43][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=37">37</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=43][sent_to]" data-text="0"><a href="mailto:tom@ata-tech.nl">tom@ata-tech.nl</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=43][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=43][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=43][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=43][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=43][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=43][insertTS]" data-text="0">2015-08-03 07:44:17</td>
        <td id="val[&amp;where%5Bid%5D=43][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=44"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=44" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=44][id]" data-text="0">44</td>
        <td id="val[&amp;where%5Bid%5D=44][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=38">38</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=44][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=44][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=44][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=44][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=44][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=44][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=44][insertTS]" data-text="0">2015-08-03 07:46:50</td>
        <td id="val[&amp;where%5Bid%5D=44][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=45"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=45" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=45][id]" data-text="0">45</td>
        <td id="val[&amp;where%5Bid%5D=45][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=38">38</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=45][sent_to]" data-text="0"><a href="mailto:messina_huybrechts_09@hotmail.com">messina_huybrechts_09@hotmail.com</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=45][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=45][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=45][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=45][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=45][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=45][insertTS]" data-text="0">2015-08-03 07:47:30</td>
        <td id="val[&amp;where%5Bid%5D=45][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=46"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=46" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=46][id]" data-text="0">46</td>
        <td id="val[&amp;where%5Bid%5D=46][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=39">39</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=46][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=46][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=46][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=46][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=46][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=46][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=46][insertTS]" data-text="0">2015-08-03 09:42:58</td>
        <td id="val[&amp;where%5Bid%5D=46][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=47"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=47" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=47][id]" data-text="0">47</td>
        <td id="val[&amp;where%5Bid%5D=47][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=39">39</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=47][sent_to]" data-text="0"><a href="mailto:m.dijkmans@upcmail.nl">m.dijkmans@upcmail.nl</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=47][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=47][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=47][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=47][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=47][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=47][insertTS]" data-text="0">2015-08-03 09:43:04</td>
        <td id="val[&amp;where%5Bid%5D=47][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=48"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=48" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=48][id]" data-text="0">48</td>
        <td id="val[&amp;where%5Bid%5D=48][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=40">40</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=48][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=48][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=48][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=48][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=48][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=48][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=48][insertTS]" data-text="0">2015-08-03 09:45:34</td>
        <td id="val[&amp;where%5Bid%5D=48][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=49"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=49" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=49][id]" data-text="0">49</td>
        <td id="val[&amp;where%5Bid%5D=49][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=40">40</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=49][sent_to]" data-text="0"><a href="mailto:jessica_vd_p@hotmail.com">jessica_vd_p@hotmail.com</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=49][sendtype]" data-text="0">email</td>
        <td id="val[&amp;where%5Bid%5D=49][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=49][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=49][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=49][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=49][insertTS]" data-text="0">2015-08-03 09:45:37</td>
        <td id="val[&amp;where%5Bid%5D=49][insertUser]" data-text="0">2</td>
      </tr>
      <tr class="odd">
        <td><input type="checkbox" name="check[]" value="where%5Bid%5D=50"> <a
          href="?username=root&amp;db=rove&amp;edit=invoice_status&amp;where%5Bid%5D=50" class="edit">bewerk</a></td>
        <td id="val[&amp;where%5Bid%5D=50][id]" data-text="0">50</td>
        <td id="val[&amp;where%5Bid%5D=50][invoice_id]" data-text="0"><a
          href="?username=root&amp;db=rove&amp;select=invoice&amp;where%5B0%5D%5Bcol%5D=id&amp;where%5B0%5D%5Bop%5D=%3D&amp;where%5B0%5D%5Bval%5D=41">41</a>
        </td>
        <td id="val[&amp;where%5Bid%5D=50][sent_to]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=50][sendtype]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=50][status]" data-text="0">invoiced</td>
        <td id="val[&amp;where%5Bid%5D=50][email_subject]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=50][email_message]" data-text="1"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=50][pdf_filename]" data-text="0"><i>NULL</i></td>
        <td id="val[&amp;where%5Bid%5D=50][insertTS]" data-text="0">2015-08-03 09:48:04</td>
        <td id="val[&amp;where%5Bid%5D=50][insertUser]" data-text="0">2</td>
      </tr>
      </tbody>
    </table>

  </div>

</template>
