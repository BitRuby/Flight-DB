                    <div class="row">
                        <div class="col-md-3 col-xs-4">
                            <div class="widget bg-white">
                                <i class="widget-circle bg-sky fa fa-rocket" aria-hidden="true"></i>
                                <span class="widget-circle-title"><?php $Queries = new Queries("SUM(id_lotu)","lot"); Content::writeContent($Queries->findInTable);?></span>
                                <span class="widget-circle-subtitle">rekordów w bazie</span>
                            </div>
                             <div class="widget bg-white">
                                <i class="widget-circle bg-grey fa fa-map-marker" aria-hidden="true"></i>
                                <span class="widget-circle-title"><?php $Queries = new Queries("SUM(id_lotniska)","lotnisko"); Content::writeContent($Queries->findInTable);?></span>
                                <span class="widget-circle-subtitle">czynne lotniska</span>
                            </div>
                            <div class="widget bg-white">
                                <i class="widget-circle bg-yellowplane fa fa-users" aria-hidden="true"></i>
                                <span class="widget-circle-title"><?php $Queries = new Queries("SUM(id_pilota)","pilot"); Content::writeContent($Queries->findInTable);?></span>
                                <span class="widget-circle-subtitle">pilotów w służbie</span>
                            </div>
                            <div class="widget bg-white">
                                <i class="widget-circle bg-junglegreen fa fa-line-chart" aria-hidden="true"></i>
                                <span class="widget-circle-title">$<?php $Queries = new Queries("round(AVG(zysk),0)","lot"); Content::writeContent($Queries->findInTable);?></span>
                                <span class="widget-circle-subtitle">średnie dochody</span>
                            </div>
                            <div class="widget bg-white">
                                <i class="widget-circle bg-softred fa fa-bar-chart" aria-hidden="true"></i>
                                <span class="widget-circle-title">$<?php $Queries = new Queries("round(AVG(strata),0)","lot"); Content::writeContent($Queries->findInTable);?></span>
                                <span class="widget-circle-subtitle">średnie starty</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="widget-title bg-sky">Statystyka lotów dla poszczególnych kontynentów</div>
                            <div class="widget bg-white">
                                <canvas id="bar-chart-grouped" width="800" height="450"></canvas>
                            </div>
                        </div>
                      
                    </div>
