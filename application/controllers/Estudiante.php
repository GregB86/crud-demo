<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Estudiante extends CI_Controller{

  public function __construct()
  {
      parent::__construct();
      $this->load->model('Estudiante_model');
  }

  public function index()
  {
      $this->load->helper('url');
      $this->load->view('estudiante/index');
  }

  public function ajax_list()
  {
      $list = $this->Estudiante_model->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $estudiante) {
          $no++;
          $row = array();
          $row[] = $estudiante->estu_id;
          $row[] = $estudiante->estu_nombre;
          $row[] = $estudiante->estu_apellido;
          $row[] = $estudiante->estu_cedula;
          $row[] = $estudiante->carr_id;
          //add html for action
          $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="editEstudiante('."'".$estudiante->estu_id."'".')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
                <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="deleteEstudiante('."'".$estudiante->estu_id."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
          $data[] = $row;
      }
      $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->Estudiante_model->count_all(),
                      "recordsFiltered" => $this->Estudiante_model->count_filtered(),
                      "data" => $data,
              );
      //output to json format
      echo json_encode($output);
  }

  public function ajax_edit($id)
  {
      $data = $this->Estudiante_model->get_by_id($id);
      echo json_encode($data);
  }

  public function ajax_add()
  {
      $this->_validate();
      $data = array(
              'estu_nombre' => $this->input->post('estu_nombre'),
              'estu_apellido' => $this->input->post('estu_apellido'),
              'estu_cedula' => $this->input->post('estu_cedula'),
              'carr_id' => $this->input->post('carr_id')
          );
      $insert = $this->Estudiante_model->save($data);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_update()
  {
      $this->_validate();
      $data = array(
              'estu_nombre' => $this->input->post('estu_nombre'),
              'estu_apellido' => $this->input->post('estu_apellido'),
              'estu_cedula' => $this->input->post('estu_cedula'),
              'carr_id' => $this->input->post('carr_id')
          );
      $this->Estudiante_model->update(array('estu_id' => $this->input->post('estu_id')), $data);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_delete($id)
  {
      $this->Estudiante_model->delete_by_id($id);
      echo json_encode(array("status" => TRUE));
  }

  private function _validate()
  {
      $data = array();
      $data['error_string'] = array();
      $data['inputerror'] = array();
      $data['status'] = TRUE;

      if($this->input->post('estu_nombre') == '')
      {
          $data['inputerror'][] = 'estu_nombre';
          $data['error_string'][] = 'Primer nombre es requerido';
          $data['status'] = FALSE;
      }

      if($this->input->post('estu_apellido') == '')
      {
          $data['inputerror'][] = 'estu_apellido';
          $data['error_string'][] = 'Primer apellido es requerido';
          $data['status'] = FALSE;
      }

      if($this->input->post('estu_cedula') == '')
      {
          $data['inputerror'][] = 'estu_cedula';
          $data['error_string'][] = 'Cedula es requerida';
          $data['status'] = FALSE;
      }

      if($this->input->post('carr_id') == '')
      {
          $data['inputerror'][] = 'carr_id';
          $data['error_string'][] = 'Debe seleccionar una carrera';
          $data['status'] = FALSE;
      }

      if($data['status'] === FALSE)
      {
          echo json_encode($data);
          exit();
      }
  }
}