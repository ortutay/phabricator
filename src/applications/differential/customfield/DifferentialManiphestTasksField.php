<?php

final class DifferentialManiphestTasksField
  extends DifferentialCustomField {

  public function getFieldKey() {
    return 'differential:maniphest-tasks';
  }

  public function getFieldKeyForConduit() {
    return 'maniphestTaskPHIDs';
  }

  public function canDisableField() {
    return false;
  }

  public function getFieldName() {
    return pht('Maniphest Tasks');
  }

  public function getFieldDescription() {
    return pht('Lists associated tasks.');
  }

  public function shouldAppearInPropertyView() {
    return true;
  }

  public function renderPropertyViewLabel() {
    return $this->getFieldName();
  }

  public function getRequiredHandlePHIDsForPropertyView() {
    if (!$this->getObject()->getPHID()) {
      return array();
    }

    return PhabricatorEdgeQuery::loadDestinationPHIDs(
      $this->getObject()->getPHID(),
      PhabricatorEdgeConfig::TYPE_DREV_HAS_RELATED_TASK);
  }

  public function renderPropertyViewValue(array $handles) {
    return $this->renderHandleList($handles);
  }

  public function shouldAppearInCommitMessage() {
    return true;
  }

  public function shouldAllowEditInCommitMessage() {
    return true;
  }

  public function getCommitMessageLabels() {
    return array(
      'Maniphest Task',
      'Maniphest Tasks',
    );
  }

  public function parseValueFromCommitMessage($value) {
    return $this->parseObjectList(
      $value,
      array(
        ManiphestPHIDTypeTask::TYPECONST,
      ));
  }

  public function getRequiredHandlePHIDsForCommitMessage() {
    return $this->getRequiredHandlePHIDsForPropertyView();
  }

  public function renderCommitMessageValue(array $handles) {
    return $this->renderObjectList($handles);
  }

  public function getProTips() {
    return array(
      pht(
        'Write "Fixes T123" in your summary to automatically close the '.
        'corresponding task when this change lands.'),
    );
  }

}
