<?php

/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *  > This library is free software; you can redistribute it and/or modify it under 
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *  > any later version.
 *  > 
 *  > This library is distributed in the hope that it will be usefull, 
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *  > for more details.
 *  > 
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**    
 *  @file DocumentoSolicitud.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 01/03/2016
 * 
 *  @class DocumentoSolicitud
 *  @brief Modelo que contiene el metodo que se encarga de buscar los documentos al usuario automaticamente con informacion
 *  relacionada a la realizacion de la solicitud. 
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 * 
 *  enviarEmail
 *  
 *
 *  @inherits
 *  
 */ 
namespace common\models\configuracion\solicitud;

use Yii;
use yii\base\Model;
use common\models\Users;
use yii\db\ActiveRecord;
use backend\models\configuracion\documentosolicitud\SolicitudDocumento;
//session_start();

class DocumentoSolicitud{

    /**
     * [enviarEmail description] metodo que envia email al usuario con la informacion que reciba como parametros
     * @param  [type] $email string [description] variable que recibe el email del contribuyente al que se le enviara el correo 
     * con la informacion de la solicitud
     * @param  [type] $solicitud string [description] variable que recibe el tipo de solicitud que realiza el contribuyente
     * @return [type]            [description] retorna la funcion que hace que envie el correo
     */
    public function Documentos()
    {


       $documentosPrincipal = SolicitudDocumento::find()->Where(['id_config_solicitud'=>$_SESSION['id'],'config_solic_documentos.inactivo' => 0]) 
                                               ->andWhere(['copia'=>0])
                                               ->joinWith('documentoRequisito')
                                               ->asArray()
                                               ->all();

       $documentosSegundarios = SolicitudDocumento::find()->Where(['id_config_solicitud'=>$_SESSION['id'],'config_solic_documentos.inactivo' => 0]) 
                                               ->andWhere(['copia'=>1])
                                               ->joinWith('documentoRequisito')
                                               ->asArray()
                                               ->all();
       
       if($documentosPrincipal == true and $documentosSegundarios == true) {

         foreach ($documentosPrincipal as $key => $value) {
                   
          $a[] = $value['documentoRequisito']['descripcion'];

          }
          foreach ($documentosSegundarios as $key => $value) {
                   
          $b[] = $value['documentoRequisito']['descripcion'];

          }

        return $doc = (['a'=>$a, 'b'=>$b]);
        
       }         


        if($documentosPrincipal == true and $documentosSegundarios == false) {

         foreach ($documentosPrincipal as $key => $value) {
                   
          $a[] = $value['documentoRequisito']['descripcion'];

          }
          $b = ['b'=>'NO REQUIERE DOCUMENTOS'];

        return $doc = (['a'=>$a, 'b'=>$b]);
        
       }


       if($documentosPrincipal == false and $documentosSegundarios == true) {

         
          foreach ($documentosSegundarios as $key => $value) {
                   
          $b[] = $value['documentoRequisito']['descripcion'];

          }
          $a = ['a'=>'NO REQUIERE DOCUMENTOS'];

        return $doc = (['a'=>$a, 'b'=>$b]);
        
       }    


       
       if($documentosPrincipal == false and $documentosSegundarios == false) {

         
          $a = ['a'=>'NO REQUIERE DOCUMENTOS'];
          $b = ['b'=>'NO REQUIERE DOCUMENTOS'];

        return $doc = (['a'=>$a, 'b'=>$b]);
        
       }      
    }

} 

 ?>