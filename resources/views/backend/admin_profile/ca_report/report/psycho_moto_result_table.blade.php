@php
$pscho = $psychomotor ?? (object)[
       'attendance' => '-',
        'punctuality' => '-',
        'neatness'=> '-',
        'honesty'=> '-',
        'musical'=> '-',
        'initiative'=> '-',
        'creativity'=> '-',
        'sport'=> '-',
        'perseverance'=> '-',	
        'cooperation'=> '-',
        ]

        @endphp


        <table class="table table-bordered table-sm mb-4 deep-border table-striped" style="width:90%;" align="center">

        <thead class="table-secondary text-center">
              <tr>
                     <th colspan="5" class="table-dark">Psychomotor / Affective Assessment</th>
              </tr>
              <tr class="fw-bold" >
                     <td>Attendance</td>
                     <td>Punctuality</td>
                     <td>Neatness</td>
                     <td>Honesty</td>
                     <td>Music</td>
              </tr>
        </thead>
        <tbody class="text-center">
              <tr>
                     <td><b>{{$pscho->attendance}}</b></td>
                     <td><b>{{$pscho->punctuality}}</b></td>
                     <td><b>{{$pscho->neatness}}</b></td>
                     <td><b>{{$pscho->honesty}}</b></td>
                     <td><b>{{$pscho->musical}}</b></td>
              </tr>

              <tr class="fw-bold table-secondary">
                     <th>initiative</th>
                     <th>creativity</th>
                     <th>sport</th>
                     <th>perseverance</th>
                     <th>co-operation</th>
              </tr>
              <tr>
                     <td><b>{{$pscho->initiative}}</b></td>
                     <td><b>{{$pscho->creativity}}</b></td>
                     <td><b>{{$pscho->sport}}</b></td>
                     <td><b>{{$pscho->perseverance}}</b></td>
                     <td><b>{{$pscho->cooperation}}</b></td>
              </tr>
        </tbody>
        </table>