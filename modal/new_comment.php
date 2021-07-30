<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 08/07/2019
 * Time: 05:56 PM
 */

?>

<div class="modal" tabindex="-1" role="dialog" id="md-comments">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comentarios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mesgs">
                    <div class="msg_history" id="msg_history">
                        <!--MSJ HISTORY-->
                    </div>

                    <div class="add-new-comment">
                        <form class="form-horizontal form-label-left input_mask" method="post" id="add-comment"
                              name="add-comment">
                            <div class="type_msg">
                                <div class="input_msg_write">
<!--                                    <input type="text" class="write_msg" id="write_msg" name="write_msg"-->
<!--                                           placeholder="Inicia una conversación..." autofocus/>-->
                                    <textarea  class="form-control write_msg" id="write_msg"
                                               name="write_msg"
                                               placeholder="Escriba un comentario..."
                                               onInput="edValueKeyPress()" <?php echo $disabled ?>
                                                                           autofocus></textarea>

                                    <input type="hidden" id="comment_user" name="user"/>
                                    <input type="hidden" id="comment_ticket" name="ticket"/>
                                    <button class="msg_send_btn" type="submit">
                                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                    </button>
                                    <span id="result"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
            </div>
        </div>
    </div>
</div>
