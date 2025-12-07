<?php

function sweetAlert()
{
    $session = session();
    $output = '';

    if ($session->getFlashdata('success')) {
        $msg = esc($session->getFlashdata('success'));
        $output .= "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: '{$msg}',
                confirmButtonColor: '#3085d6'
            })
        </script>
        ";
    }

    if ($session->getFlashdata('error')) {
        $msg = esc($session->getFlashdata('error'));
        $output .= "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '{$msg}',
                confirmButtonColor: '#d33'
            })
        </script>
        ";
    }

    if ($session->getFlashdata('warning')) {
        $msg = esc($session->getFlashdata('warning'));
        $output .= "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: '{$msg}',
            })
        </script>
        ";
    }

    return $output;
}