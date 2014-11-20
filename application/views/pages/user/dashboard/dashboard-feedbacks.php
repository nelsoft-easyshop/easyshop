 <div class="row">
    
    <div class="col-xs-12">
        <div class="form-filter">
            <label class="label-sort">Sort by:</label>
            <select class="select-filter-item filter-feedbacks">
                <option>All</option>
                <option>Feedback as seller</option>
                <option>Feedback as buyer</option>
                <option>Feedback for seller</option>
                <option>Feedback for buyer</option>
            </select>
        </div>
    </div>
</div>
<br/>
 <div class="row">
        <div class="col-md-2 col-feedback-user">
            <div class="user-feeder">
                <div class="user-image-container">
                    <center class="center-image">
                        <div class="div-user-image">
                            <a href="/justineduazo">
                                <img src="/assets/images/products/nikon-logo.png" class="img-user-image"/>
                            <a/>
                        </div>
                    </center>
                </div>
                <p class="p-user-name">
                    <a href="/justineduazo">
                        justineduazo
                    </a>
                </p>
                <p class="p-date-feedback">
                    2nd September, 2014
                </p>
            </div>
            <table class="table-feed-mobile">
                <tbody>
                    <tr>
                        <td>
                            <div class="div-user-image">
                                <a href="/justineduazo">
                                    <img src="/assets/images/products/nikon-logo.png" class="img-user-image"/>
                                <a/>
                            </div>
                        </td>
                        <td class="td-info-mobile">
                            <p class="p-user-name">
                                <a href="/justineduazo">
                                    justineduazo
                                </a>
                            </p>
                            <p class="p-date-feedback">
                                2nd September, 2014
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-10 col-feedback-container" style="padding-left: 0px;">
            <div class="panel panel-default panel-feedback-item">
                <div class="row">
                    <div class="col-md-6">
                        <p class="feedback-cat-title">Feedback as seller</p>
                        <table>
                            <tr>
                                <td class="td-feedback-criteria">Item quality</td>
                                <td>
                                    <span>
                                        <?php for($i = 0; $i < 5; $i++): ?>
                                            <i class="fa fa-star star-feed star-active"></i>
                                        <?php endfor; ?>
                                        
                                        <?php for($i = 0; $i < 5 - 5; $i++): ?>
                                            <i class="fa fa-star star-feed"></i>
                                        <?php endfor; ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-feedback-criteria">Communication</td>
                                <td>
                                    <span>
                                        <?php for($i = 0; $i < 5; $i++): ?>
                                            <i class="fa fa-star star-feed star-active"></i>
                                        <?php endfor; ?>
                                        
                                        <?php for($i = 0; $i < 5 - 5; $i++): ?>
                                            <i class="fa fa-star star-feed"></i>
                                        <?php endfor; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-feedback-criteria">Shipment time</td>
                                <td>
                                    <span>
                                        <?php for($i = 0; $i < 4; $i++): ?>
                                            <i class="fa fa-star star-feed star-active"></i>
                                        <?php endfor; ?>
                                        
                                        <?php for($i = 0; $i < 5 - 4; $i++): ?>
                                            <i class="fa fa-star star-feed"></i>
                                        <?php endfor; ?>

                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-item-message">
                        Test tets
                    </div>
                </div>
            </div>
        </div>
    </div>

    <center>
        <ul class="pagination pagination-items" data-lastpage="1">
            <li data-page="1" class="extremes previous">
                <a href="javascript:void(0)">
                    <span> « </span>
                </a>
            </li>
            <li class="active individual" data-page="1">
                <a href="javascript:void(0)">
                    <span>1</span>
                </a>
            </li>
            <li data-page="1" class="extremes next">
                <a href="javascript:void(0)">
                    <span> » </span>
                </a>
            </li>
        </ul>
    </center>
    <br/>