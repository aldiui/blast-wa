import React from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import {
  Button,
  Card,
  CardBody,
  CardFooter,
  CardHeader,
  FormControl,
  FormErrorMessage,
  FormLabel,
  Heading,
  Icon,
  Input,
  Select,
  SimpleGrid,
  Text,
  Textarea,
} from "@chakra-ui/react";
import { ArrowLeftIcon, BookmarkIcon } from "@heroicons/react/16/solid";
import AdminLayout from "../../../Layouts/AdminLayout";

const CreateSiswa = ({ auth, sessions, kelas }) => {
  const { data, setData, post, processing, errors } = useForm({
    nis: "",
    nama: "",
    orang_tua: "",
    no_telepon: "",
    alamat: "",
    id_kelas: "",
  });

  const submit = (e) => {
    e.preventDefault();
    post("/siswa");
  };

  return (
    <AdminLayout auth={auth} sessions={sessions}>
      <Head title="Tambah Siswa" />
      <Card w="full" p={2} h={"auto"}>
        <CardHeader pb={0}>
          <Heading size="md" fontWeight="bold">
            Tambah Siswa
          </Heading>
        </CardHeader>
        <form onSubmit={submit}>
          <CardBody pb={0}>
            <SimpleGrid columns={{ base: 1, xl: 2 }} spacing={6}>
              <FormControl isInvalid={errors.nis}>
                <FormLabel htmlFor="nis" fontSize={"sm"}>
                  Nis
                  <Text display={"inline"} color="red">
                    *
                  </Text>
                </FormLabel>
                <Input
                  type="number"
                  id="nis"
                  value={data.nis}
                  onChange={(e) => setData("nis", e.target.value)}
                />
                {errors.nis && (
                  <FormErrorMessage fontSize={"xs"}>
                    {errors.nis}
                  </FormErrorMessage>
                )}
              </FormControl>
              <FormControl isInvalid={errors.nama}>
                <FormLabel htmlFor="nama" fontSize={"sm"}>
                  Nama
                  <Text display={"inline"} color="red">
                    *
                  </Text>
                </FormLabel>
                <Input
                  type="text"
                  id="nama"
                  value={data.nama}
                  onChange={(e) => setData("nama", e.target.value)}
                />
                {errors.nama && (
                  <FormErrorMessage fontSize={"xs"}>
                    {errors.nama}
                  </FormErrorMessage>
                )}
              </FormControl>
              <FormControl isInvalid={errors.orang_tua}>
                <FormLabel htmlFor="orang_tua" fontSize={"sm"}>
                  Orang Tua
                  <Text display={"inline"} color="red">
                    *
                  </Text>
                </FormLabel>
                <Input
                  type="text"
                  id="orang_tua"
                  value={data.orang_tua}
                  onChange={(e) => setData("orang_tua", e.target.value)}
                />
                {errors.orang_tua && (
                  <FormErrorMessage fontSize={"xs"}>
                    {errors.orang_tua}
                  </FormErrorMessage>
                )}
              </FormControl>
              <FormControl mb={3} isInvalid={errors.id_kelas}>
                <FormLabel htmlFor="id_kelas" fontSize={"sm"}>
                  Kelas
                  <Text display={"inline"} color="red">
                    *
                  </Text>
                </FormLabel>
                <Select
                  id="id_kelas"
                  value={data.id_kelas}
                  onChange={(e) => setData("id_kelas", e.target.value)}
                >
                  <option value="">Pilih Kelas</option>
                  {kelas.map((kls) => (
                    <option key={kls.id} value={kls.id}>
                      {kls.nama}
                    </option>
                  ))}
                </Select>
                {errors.id_kelas && (
                  <FormErrorMessage fontSize={"xs"}>
                    {errors.id_kelas}
                  </FormErrorMessage>
                )}
              </FormControl>
              <FormControl isInvalid={errors.no_telepon}>
                <FormLabel htmlFor="no_telepon" fontSize={"sm"}>
                  No. Telepon
                  <Text display={"inline"} color="red">
                    *
                  </Text>
                </FormLabel>
                <Input
                  type="number"
                  id="no_telepon"
                  value={data.no_telepon}
                  onChange={(e) => setData("no_telepon", e.target.value)}
                />
                {errors.no_telepon && (
                  <FormErrorMessage fontSize={"xs"}>
                    {errors.no_telepon}
                  </FormErrorMessage>
                )}
              </FormControl>
              <FormControl isInvalid={errors.alamat}>
                <FormLabel htmlFor="alamat" fontSize={"sm"}>
                  Alamat
                  <Text display={"inline"} color="red">
                    *
                  </Text>
                </FormLabel>
                <Textarea
                  id="alamat"
                  value={data.alamat}
                  onChange={(e) => setData("alamat", e.target.value)}
                ></Textarea>
                {errors.alamat && (
                  <FormErrorMessage fontSize={"xs"}>
                    {errors.alamat}
                  </FormErrorMessage>
                )}
              </FormControl>
            </SimpleGrid>
          </CardBody>
          <CardFooter>
            <Button
              type="submit"
              colorScheme="green"
              isLoading={processing}
              loadingText="Simpan"
            >
              <Icon as={BookmarkIcon} mr={2} />
              Simpan
            </Button>
            <Button as={Link} href={"/siswa"} colorScheme="gray" ml={3}>
              <Icon as={ArrowLeftIcon} mr={2} />
              Kembali
            </Button>
          </CardFooter>
        </form>
      </Card>
    </AdminLayout>
  );
};

export default CreateSiswa;
