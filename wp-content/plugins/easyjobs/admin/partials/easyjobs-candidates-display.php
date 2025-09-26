<?php
/**
 * Candidate list page for easyjobs
 *
 * @link       https://easy.jobs
 * @since      1.0.0
 *
 * @package    Easyjobs
 * @subpackage Easyjobs/admin/partials
 */

?>

<div class="wrap">
	<hr class="wp-header-end">
	<input type="hidden" class="easyjobs-job-id" value="<?php echo esc_attr( wp_unslash( $_GET['job-id'] ) ); ?>">
	<input type="hidden" class="easyjobs-ai-enabled" value="<?php echo esc_html( $ai_enabled ); ?>">
	<div class="easy-page-body">
		<main class="content-area">
			<?php require EASYJOBS_ADMIN_DIR_PATH . '/partials/easyjobs-admin-header.php'; ?>
			<!-- content body -->
			<div class="content-area__body">
				<section class="candidates-section section-gap">
					<div class="d-flex flex-wrap align-items-start justify-content-between">
						<div class="back-button mt-0">
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-all-jobs' ) ); ?>"
							   class="back-button__icon">
								<i class="easyjobs-icon easyjobs-back"></i>
							</a>
							<div
								class="back-button__text d-none d-md-block"><?php esc_html_e( 'Back To Jobs', 'easyjobs' ); ?></div>
						</div>
						<div class="dropdown ej-job-candidates-action ml-auto mr-3">
							<button class="button info-button dropdown-toggle" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false"><?php esc_html_e( 'More', 'easyjobs' ); ?></button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item" href="#" data-toggle="modal"
								   data-target="#ej-invite-candidate-modal"><?php esc_html_e( 'Invite candidates', 'easyjobs' ); ?></a>
								<a class="dropdown-item"
								   href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-admin&job-id=' . esc_attr( $_GET['job-id'] ) . '&view=pipeline' ) ); ?>"><?php esc_html_e( 'Pipeline', 'easyjobs' ); ?></a>
								<a class="dropdown-item ej-export-candidates"
								   href="#"><?php esc_html_e( 'Export', 'easyjobs' ); ?></a>
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                   data-target="#ej-pending-candidate-modal"><?php esc_html_e( 'Pending candidates', 'easyjobs' ); ?></a>
							</div>
						</div>
					</div>
					<div class="section-title-wrap">
						<?php if ( ! empty( $job ) ) : ?>
							<div class="mt-1">
								<div class="section-title"><?php echo esc_html( $job->title ); ?></div>
								<p class="section-label">
									<?php echo esc_html( date( 'd F, Y', strtotime( str_replace( ', ', '', $job->expire_at ) ) ) ); ?>
								</p>
							</div>
						<?php endif; ?>
						<div class="d-flex ej-candidate-search-filter align-items-center">
							<div class="ej-job-candidate-filter">
								<div class="select-option">
									<select>
										<option value="select"
												selected><?php esc_html_e( 'Sort candidates', 'easyjobs' ); ?></option>
										<?php foreach ( Easyjobs_Helper::candidate_sort_options() as $sort_option ) : ?>
											<option
												value="<?php echo esc_html( $sort_option['value'] ); ?>"><?php echo __( esc_html( $sort_option['title'] ), 'easyjobs' ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<form action="" class="form-filter mb-0 mt-1 ej-candidate-search">
								<div class="search-bar mr-0">
									<input type="text" class="easyjobs-search-candidates" placeholder="Search Candidates Name . . ."/>
								</div>
							</form>
						</div>
					</div>
					<div class="candidates-box">
						<div class="candidates-filter-box easyjobs-filter">
							<div class="filter-card">
								<div class="filter-card__heading gutter-10">
									<div class="section-title"><?php esc_html_e( 'Filter', 'easyjobs' ); ?></div>
								</div>
								<div class="filter-card__body gutter-10">
									<ul>
										<li>
											<label class="checkbox">
												<input type="checkbox" id="candidate_filter_1" name="candidate_filter"
													   class="filter" value="1"/>
												<span><?php esc_html_e( 'New', 'easyjobs' ); ?></span>
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input type="checkbox" id="candidate_filter_2" name="candidate_filter"
													   class="filter" value="2"/>
												<span><?php esc_html_e( 'Rated', 'easyjobs' ); ?></span>
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input type="checkbox" id="candidate_filter_3" name="candidate_filter"
													   class="filter" value="3"/>
												<span><?php esc_html_e( 'Not rated', 'easyjobs' ); ?></span>
											</label>
										</li>
									</ul>
								</div>
							</div>
							<?php if ( ! empty( $pipelines ) ) : ?>
								<div class="filter-card">
									<div class="filter-card__heading gutter-10">
										<div class="section-title">
											<?php esc_html_e( 'Filter By Stage', 'easyjobs' ); ?>
										</div>
									</div>
									<div class="filter-card__body gutter-10">
										<ul>
											<?php foreach ( $pipelines as $key => $pipeline ) : ?>
												<li>
													<label class="checkbox">
														<input type="checkbox" value="<?php echo esc_html( $pipeline->id ); ?>"
															   id="candidate_stage_filter_<?php echo esc_html( $pipeline->id ); ?>"
															   name="candidate_filter_stage" class="stage-filter"/>
														<span><?php echo esc_html( $pipeline->name ); ?></span>
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<!-- data table -->
						<div class="data-table candidates-table">
							<div class="table-wrap">
								<div class="table">
									<?php if ( ! empty( $candidates ) ) : ?>
										<div class="table__row table__head">
											<div class="table-cell"><?php esc_html_e( 'Name', 'easyjobs' ); ?></div>
											<?php if ( $ai_enabled ) : ?>
												<div class="table-cell"><?php esc_html_e( 'Score', 'easyjobs' ); ?></div>
											<?php endif; ?>
											<div class="table-cell candidate-apply-time">
											<?php
											esc_html_e(
												'Date',
												'easyjobs'
											);
											?>
													</div>
											<div class="table-cell"><?php esc_html_e( 'Stage', 'easyjobs' ); ?></div>
											<div class="table-cell"><?php esc_html_e( 'Rating', 'easyjobs' ); ?></div>
										</div>
										<div class="table__body">
											<?php foreach ( $candidates as $candidate ) : ?>
												<div class="table__row"
													 data-candidate-id="<?php echo esc_html( $candidate->id ); ?>">
													<div class="table-cell user__info">
														<a href="<?php echo esc_url( admin_url( 'admin.php?page=easyjobs-admin&candidate-id=' . $candidate->id ) ); ?>"
														   class="d-flex align-items-center">
															<div class="user__image">
																<img
																	src="<?php echo esc_url( $candidate->user->profile_image ); ?>"
																	alt="" class="w-100 img-fluid">
															</div>
															<h4 class="user__name"><?php echo esc_html( $candidate->user->name ); ?></h4>
														</a>
													</div>
													<?php if ( $ai_enabled ) : ?>
														<div class="table-cell user-ai-score has-ai-score">
															<?php if ( ! empty( $candidate->final_ai_score ) ) : ?>
																<div class="progress">
																	<div class="progress-bar" role="progressbar"
																		 style="width: <?php echo esc_html( $candidate->final_ai_score ); ?>%;"
																		 aria-valuenow="<?php echo esc_html( $candidate->final_ai_score ); ?>"
																		 aria-valuemin="0"
																		 aria-valuemax="100"><?php echo esc_html( $candidate->final_ai_score ); ?>
																		%
																	</div>
																</div>
																<?php Easyjobs_Helper::get_ai_score_details( $candidate->scores ); ?>
															<?php endif; ?>
														</div>
													<?php endif; ?>
													<div
														class="table-cell candidate-apply-time"><?php echo esc_html( $candidate->created_at ); ?></div>
													<div class="table-cell job__status">
														<div
															class="semi-button h-modified <?php echo ! empty( $candidate->pipeline->name ) ? esc_html( Easyjobs_Helper::get_pipeline_label( $candidate->pipeline->name ) ) : ''; ?> w-100">
															<?php echo ! empty( $candidate->pipeline->name ) ? esc_html( $candidate->pipeline->name ) : ''; ?>
														</div>
													</div>
													<div class="table-cell user-rate">
														<div class="user__text__ratting">
															<?php Easyjobs_Helper::rating_icon( $candidate->rating ); ?>
														</div>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php else : ?>
										<div class="table__row table__head">
											<div class="table-cell">
												<?php esc_html_e( 'No candidates found', 'easyjobs' ); ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</section>
				<div id="ej-invite-candidate-modal" class="modal fade ej-modal ej-invite-candidate-modal">
					<div role="document" class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title text-uppercase">Invite candidates</h4>
								<button type="button" data-dismiss="modal" aria-label="Close" class="close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body invite__candidate">
								<form class="ej-invite-candidate"
									  data-nonce="<?php echo esc_html( wp_create_nonce( 'easyjobs_invite_candidate' ) ); ?>"
									  data-job-id="<?php echo esc_attr( $_GET['job-id'] ); ?>">
									<label>Email*</label>
									<div class="input-group mb-3 mt-1">
										<input type="email" name="email" placeholder="user@easy.jobs"
											   class="form-control user-email">
										<button type="submit" class="button info-button text-capitalize">invite</button>
									</div>
									<div class="error-message mb-3"></div>
								</form>
								<div class="data-table user-table invite__candidate--table">
									<div class="table-wrap d-none">
										<div class="table table-modal">
											<div class="table__row table__head">
                                                <div class="table-cell">
													<?php esc_html_e('Name', 'easyjobs');?>
                                                </div>
                                                <div class="table-cell">
													<?php esc_html_e('Email', 'easyjobs');?>
                                                </div>
												<div class="table-cell" style="width: 110px;">
													<span class="d-flex justify-content-end">
                                                        <?php esc_html_e('Actions', 'easyjobs');?>
                                                    </span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer d-flex justify-content-between"></div>
						</div>
					</div>
				</div>
				<div id="ej-pending-candidate-modal" class="modal fade ej-modal ej-pending-candidate-modal">
					<div role="document" class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title text-uppercase">
                                    <?php esc_html_e('Pending candidates', 'easyjobs');?>
                                </h4>
								<button type="button" data-dismiss="modal" aria-label="Close" class="close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body invite__candidate">
								<div class="data-table user-table invite__candidate--table">
									<div class="table-wrap d-none">
										<div class="table table-modal">
											<div class="table__row table__head">
												<div class="table-cell">
                                                    <?php esc_html_e('Name', 'easyjobs');?>
                                                </div>
												<div class="table-cell">
													<?php esc_html_e('Email', 'easyjobs');?>
                                                </div>
												<div class="table-cell">
													<?php esc_html_e('Updated on', 'easyjobs');?>
                                                </div>
												<div class="table-cell" style="width: 110px;">
													<span class="d-flex justify-content-end">
                                                        <?php esc_html_e('Actions', 'easyjobs');?>
                                                    </span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer d-flex justify-content-between"></div>
						</div>
					</div>
				</div>
			</div>
		</main>
	</div>
</div>
