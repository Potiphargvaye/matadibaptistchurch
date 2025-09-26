<div class="wrap">
    <hr class="wp-header-end">
    <div class="easyjobs-wrapper admin-area">
        <div class="easy-page-body">
            <main class="content-area">
				<?php require EASYJOBS_ADMIN_DIR_PATH . '/partials/easyjobs-admin-header.php'; ?>
                <!-- content body -->
                <div class="content-area__body">
					<?php if ( ! empty( $job ) && ! empty( $pipelines ) ) : ?>
                        <section class="pipeline-section">
                            <div class="d-flex justify-content-between my-5">
                                <div class="back-button m-0">
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-all-jobs' ) ); ?>"
                                       class="back-button__icon">
                                        <i class="easyjobs-icon easyjobs-back"></i>
                                    </a>
                                    <div class="back-button__text d-none d-md-block">
                                        <?php esc_html_e( 'Back To Jobs', 'easyjobs' ); ?>
                                    </div>
                                    <div class="section-title d-block d-md-none ml-4">
										<?php echo esc_html( $job->title ); ?>
                                    </div>
                                </div>
                                <div class="dropdown pipeline-action ml-auto mr-3 d-none d-md-block">
                                    <button class="button white-button dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"><?php esc_html_e( 'Job Menu', 'easyjobs' ); ?></button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item"
                                           href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-all-jobs' ) ); ?>"><?php esc_html_e( 'All jobs', 'easyjobs' ); ?></a>
                                        <a class="dropdown-item"
                                           href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-admin&job-id=' . $job->id . '&view=candidates' ) ); ?>"><?php esc_html_e( 'Candidates', 'easyjobs' ); ?></a>
                                    </div>
                                </div>
                                <button class="edit-button d-none d-md-flex mr-3" data-toggle="modal"
                                        data-target="#pipeline-modal">
                                    <span class="edit-icon"><i class="easyjobs-icon easyjobs-pencil"></i></span>
                                    <span> <?php esc_html_e( 'Edit Pipeline', 'easyjobs' ); ?> </span>
                                </button>
                                <div class="dropdown pipeline-move-btn">
                                    <button class="button primary-button dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
										<?php esc_html_e( 'Move To Stage', 'easyjobs' ); ?>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
										<?php foreach ( $pipelines as $pipeline ) : ?>
                                            <a class="dropdown-item change-pipeline" href="#"
                                               data-key="<?php echo esc_html( $pipeline->id ); ?>"
                                               data-job-id="<?php echo esc_html( $job->id ); ?>">
												<?php echo esc_html( $pipeline->name ); ?>
                                            </a>
										<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="section-title-wrap">
                                <div class="active-pipeline pipeline-button">
                                    <span><?php echo esc_html( $pipelines[0]->name ); ?></span>
                                    <span
                                        class="candidates-number"><?php echo count( $pipelines[0]->applicants ); ?></span>
                                </div>
                                <div class="d-none d-md-block job-title">
                                    <div class="section-title"><?php echo esc_html( $job->title ); ?></div>
                                    <span class="section-label">
                                        <?php echo esc_html( date( 'd F, Y', strtotime( str_replace( ', ', '', $job->expire_at ) ) ) ); ?>
                                    </span>
                                </div>
                                <div class="nav pipeline-toggler" id="nav-tab" role="tablist">
                                    <a class="toggler-button active" data-toggle="tab" href="#pipeline-box" role="tab"
                                       aria-controls="pipeline-box" aria-selected="true"
                                       data-job-id="<?php echo esc_html( $job->id ); ?>">
                                        <div class="icon"><i class="easyjobs-icon easyjobs-thumbnail"></i></div>
                                    </a>
                                    <a class="toggler-button" data-toggle="tab" href="#pipeline-list-box" role="tab"
                                       aria-controls="pipeline-list-box" aria-selected="true"
                                       data-job-id="<?php echo esc_html( $job->id ); ?>">
                                        <div class="icon"><i class="easyjobs-icon easyjobs-trello"></i></div>
                                    </a>
                                </div>
                            </div>
                            <div class="tab-content pipeline-tab-content">
                                <div id="pipeline-box" class="tab-pane fade show active">
                                    <div class="pipeline-box">
                                        <div class="pipeline-menu">
                                            <div class="pipeline-hamburger">
                                                <div class="hamburger-toggler"></div>
                                            </div>
                                            <ul class="pipeline-nav nav nav-tabs flex-column"
                                                id="pipeline-tab" role="tablist">
												<?php foreach ( $pipelines as $key => $pipeline ) : ?>
                                                    <li class="nav-item" role="presentation">
                                                        <a class="pipeline-button <?php echo esc_attr( 'pipeline-' . Easyjobs_Helper::get_tab_name( $pipeline->name ) ); ?> <?php echo $key == 0 ? 'active' : ''; ?> <?php
                                                        echo ( $pipeline->id == 'selected' ) || ( $pipeline->id == 'rejected' ) ?
															esc_attr( $pipeline->id ) : '';
														?> " data-toggle="tab"  href="#<?php echo esc_attr( Easyjobs_Helper::get_tab_name( $pipeline->name ) ); ?>"  role="tab" aria-controls="<?php echo esc_attr( Easyjobs_Helper::get_tab_name( $pipeline->name ) ); ?>"
                                                           aria-selected="<?php echo $key == 0 ? 'true' : 'false'; ?>">
                                                            <span><?php echo esc_html( $pipeline->name ); ?></span>
                                                            <span
                                                                class="candidates-number"><?php echo esc_html( count( $pipeline->applicants ) ); ?> </span>
                                                        </a>
                                                    </li>
												<?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="pipeline-content tab-content">
											<?php foreach ( $pipelines as $k => $pipeline ) : ?>
                                                <div
                                                    class="tab-pane pipeline-tab fade <?php echo $k == 0 ? esc_attr( 'show active' ) : ''; ?>"
                                                    id="<?php echo esc_attr( Easyjobs_Helper::get_tab_name( $pipeline->name ) ); ?>"
                                                    role="tabpanel"
                                                    aria-labelledby="<?php echo esc_attr( Easyjobs_Helper::get_tab_name( $pipeline->name ) ); ?>">
                                                    <div class="row row-cols-xl-3">
														<?php foreach ( $pipeline->applicants as $applicant ) : ?>
                                                            <div class="col">
                                                                <div class="pipeline-card">
                                                                    <div class="user__image">
                                                                        <img
                                                                            src="<?php echo esc_url( $applicant->user->profile_image ); ?>"
                                                                            alt="" class="w-100 img-fluid"/>
                                                                    </div>
                                                                    <div class="user__details">
                                                                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-admin&candidate-id=' . $applicant->id ) ); ?>"
                                                                           class="user__name">
																			<?php echo esc_html( $applicant->user->name ); ?>
                                                                        </a>
                                                                        <div class="d-flex">
                                                                            <?php if(!empty($applicant->user->nationality)): ?>
                                                                            <span
                                                                                class="user__address"><?php echo esc_html( $applicant->user->nationality ); ?>
                                                                            </span>
                                                                            &nbsp;
                                                                            <?php endif; ?>
                                                                            <p class="user__experience"> <?php echo esc_html( $applicant->user->experience ); ?><?php esc_html_e( 'Years', 'easyjobs' ); ?></p>
                                                                        </div>
                                                                        <div
                                                                            class="d-flex justify-content-between mt-3">
                                                                            <div class="user__text__ratting">
																				<?php Easyjobs_Helper::rating_icon( $applicant->rating ); ?>
                                                                            </div>
                                                                            <div class="application-duration"><?php echo esc_html( $applicant->updated_diff ); ?></div>
                                                                        </div>
                                                                    </div>
                                                                    <label class="checkbox pipeline-checkbox">
                                                                        <input type="checkbox" class="applicant"
                                                                               value="<?php echo esc_attr( $applicant->id ); ?>"
                                                                               id="applicant-<?php echo esc_attr( $applicant->id ); ?>"/>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
														<?php endforeach; ?>
                                                    </div>
                                                </div>
											<?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="pipeline-list-box" class="tab-pane fade">
                                    <div class="pipeline-list-box">
										<?php foreach ( $pipelines as $key => $pipeline ) : ?>
                                            <div
                                                class="pipeline__board 
                                                <?php
                                                echo ( $pipeline->id == 'selected' ) || ( $pipeline->id == 'rejected' ) ?
													esc_attr( $pipeline->id ) : '';
												?>
                                                    ">
                                                <div class="pipeline__board--title">
                                                    <h5><?php echo esc_html( ucfirst( $pipeline->name ) ); ?></h5><span
                                                        class="candidates-number"><?php echo esc_html( count( $pipeline->applicants ) ); ?></span>
                                                </div>
                                                <ul class="pipeline__board--content"
                                                    id="<?php echo 'pipeline-' . esc_attr( $pipeline->id ); ?>"
                                                    data-pipeline-id="<?php echo esc_attr( $pipeline->id ); ?>"
                                                    data-job-id="<?php echo esc_attr( $job->id ); ?>">
													<?php if ( ! empty( $pipeline->applicants ) ) : ?>
														<?php foreach ( $pipeline->applicants as $applicant ) : ?>
                                                            <li class="pipeline-card"
                                                                id="applicant-<?php echo esc_attr( $applicant->id ); ?>"
                                                                data-applicant-id="<?php echo esc_attr( $applicant->id ); ?>">
                                                                <div class="user__image">
                                                                    <img
                                                                        src="<?php echo esc_url( $applicant->user->profile_image ); ?>"
                                                                        alt="" class="w-100 img-fluid"/>
                                                                </div>
                                                                <div class="user__details">
                                                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-admin&candidate-id=' . $applicant->id ) ); ?>"
                                                                       class="user__name"><?php echo esc_html( $applicant->user->name ); ?></a>
                                                                    <div class="d-flex">
																		<?php if ( ! empty( $applicant->user->address ) ) : ?>
                                                                            <span class="user__address">
																			    <?php echo esc_html( $applicant->user->address ); ?>
                                                                            </span>
																		<?php endif; ?>
																		<?php if ( ! empty( $applicant->user->experience ) ) : ?>
                                                                            <p class="user__experience">
																				<?php echo esc_html( $applicant->user->experience ); ?>
																				<?php esc_html_e( 'Years', 'easyjobs' ); ?>
                                                                            </p>
																		<?php endif; ?>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mt-3">
                                                                        <div class="user__text__ratting">
																			<?php Easyjobs_Helper::rating_icon( $applicant->rating ); ?>
                                                                        </div>
                                                                        <div class="application-duration"><?php echo esc_attr( $applicant->updated_diff ); ?></div>
                                                                    </div>
                                                                </div>
                                                            </li>
														<?php endforeach; ?>
													<?php else : ?>
                                                        <li class="pipeline-card-not-found">
                                                            <p> No candidates found. </p>
                                                        </li>
													<?php endif; ?>
                                                </ul>
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div id="pipeline-modal" class="modal fade custom-fields">
                            <div role="document" class="modal-dialog modal-lg modal-dialog-centered">
                                <form class="save-pipeline"
                                      data-nonce="<?php echo esc_attr(wp_create_nonce( 'easyjobs_save_pipeline' )); ?>"
                                      data-job-id="<?php echo esc_attr( $job->id ); ?>">
                                    <div class="modal-content">
                                        <div class="modal-header"><h4 class="modal-title text-uppercase">edit job
                                                pipeline</h4>
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                                    aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
											<?php if ( ! empty( $pipeline_templates ) ) : ?>
                                                <div class="form-filter">
                                                    <div class="form-group flex-grow-1">
                                                        <div class="d-flex justify-content-between">
                                                            <label>Select A Template</label>
                                                        </div>
                                                        <div class="select-option">
                                                            <select class="ej-pipeline-template-select">
                                                                <option value="">Select A Template</option>
                                                                <option value="Applied, Shortlist, Interview">
                                                                    Default template
                                                                </option>
																<?php foreach ( $pipeline_templates as $template ) : ?>
                                                                    <option
                                                                        value="<?php echo esc_attr( implode( ',', $template->steps ) ); ?>">
																		<?php echo esc_html( $template->name ); ?>
                                                                    </option>
																<?php endforeach; ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
											<?php endif; ?>
                                            <div class="form-group pipeline-step-list">
                                                <label>Pipeline Steps</label>
                                                <div class="pipeline-main-wrapper">
													<?php foreach ( $pipelines as $stage ) : ?>
														<?php
														if ( $stage->id === 'selected' || $stage->id === 'rejected' ) {
															continue;
														}
														?>
                                                        <div class="input-wrapper pipeline-wrapper">
                                                            <input type="text" name="pipeline[]" disabled="disabled"
                                                                   class="form-control pipeline-stage <?php echo strtolower( $stage->name ) === 'applied' ? esc_attr( 'default-step' ) : ''; ?>"
                                                                   value="<?php echo esc_attr( $stage->name ); ?>"
                                                                   data-stage-id="<?php echo esc_attr( $stage->id ); ?>">
															<?php if ( ! $stage->is_fixed ) : ?>
                                                                <a href="#" class="input-wrapper-append delete-stage"
                                                                   draggable="false">
                                                                    <i class="easyjobs-icon easyjobs-delete"></i>
                                                                </a>
															<?php endif; ?>
                                                        </div>
													<?php endforeach; ?>
                                                    <div class="extra-stage-wrapper">
                                                        <div class="input-wrapper pipeline-wrapper">
                                                            <input type="text" disabled
                                                                   class="form-control pipeline-stage default-step"
                                                                   value="Selected"/>
                                                        </div>
                                                        <div class="input-wrapper pipeline-wrapper">
                                                            <input type="text" disabled
                                                                   class="form-control pipeline-stage default-step"
                                                                   value="Rejected"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12"><label>New Pipeline Step Name</label></div>
                                                <div class="col-md-9">
                                                    <input type="text" placeholder="New step title" class="form-control"
                                                           id="add_new_stage" name="add_new_stage">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="button semi-button-info w-100 add-new-stage">Add
                                                        New
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-between">
                                            <button data-dismiss="modal" class="button semi-button-info">Back</button>
                                            <button type="submit" class="button info-button">Save Pipeline</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
					<?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>
