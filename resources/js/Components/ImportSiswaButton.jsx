import React from "react";
import {
  Button,
  Modal,
  ModalOverlay,
  ModalContent,
  ModalHeader,
  ModalFooter,
  ModalBody,
  ModalCloseButton,
  useDisclosure,
  Icon,
  FormControl,
  FormLabel,
  Input,
} from "@chakra-ui/react";
import { ArrowDownIcon, XMarkIcon } from "@heroicons/react/16/solid";

const ImportSiswaButton = () => {
  const { isOpen, onOpen, onClose } = useDisclosure();

  return (
    <>
      <Button
        onClick={onOpen}
        colorScheme="orange"
        variant="solid"
        loadingText="Import"
        size="sm"
        mr={2}
      >
        <Icon as={ArrowDownIcon} mr={2} />
        Import
      </Button>

      <Modal isOpen={isOpen} onClose={onClose} size="lg">
        <ModalOverlay />
        <ModalContent m={6}>
          <ModalHeader>Import Excel Siswa</ModalHeader>
          <ModalCloseButton />
          <ModalBody>
            <FormControl>
              <FormLabel>File</FormLabel>
              <Input type="file" />
            </FormControl>
          </ModalBody>

          <ModalFooter textAlign={"center"}>
            <Button size={"sm"} colorScheme="gray" onClick={onClose}>
              <Icon as={XMarkIcon} mr={2} />
              Batal
            </Button>
          </ModalFooter>
        </ModalContent>
      </Modal>
    </>
  );
};

export default ImportSiswaButton;
